FROM php:8.5-cli@sha256:1954ff5cd21f222c992b79d25e403b2600cec829678d5bb7076883f3a44c0d6e AS builder

# hadolint ignore=DL3008
RUN apt-get update && \
    apt-get install --no-install-recommends -y libzip-dev && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install zip

# Install composer.
# @see https://getcomposer.org/download
# renovate: datasource=github-releases depName=composer/composer extractVersion=^(?<version>.*)$
ENV COMPOSER_ALLOW_SUPERUSER=1
# hadolint ignore=DL4006
RUN version=2.8.10 && \
    curl -sS https://getcomposer.org/download/${version}/composer.phar.sha256sum | awk '{ print $1, "composer.phar" }' > composer.phar.sha256sum && \
    curl -sS -o composer.phar https://getcomposer.org/download/${version}/composer.phar && \
    sha256sum -c composer.phar.sha256sum && \
    chmod +x composer.phar && \
    mv composer.phar /usr/local/bin/composer && \
    rm composer.phar.sha256sum && \
    composer --version && \
    composer clear-cache

# Install Box as a standalone tool to compile the PHAR. Fetching it separately
# (rather than as a dev dependency) lets the application be installed with
# --no-dev, so no build or dev tooling ends up in the compiled artifact.
# @see https://github.com/box-project/box/releases
# renovate: datasource=github-releases depName=box-project/box
RUN box_version=4.7.0 && \
    curl -fsSL -o /usr/local/bin/box "https://github.com/box-project/box/releases/download/${box_version}/box.phar" && \
    chmod +x /usr/local/bin/box && \
    box --version

WORKDIR /app

COPY composer.json composer.lock /app/

RUN COMPOSER_MEMORY_LIMIT=-1 composer install -n --ansi --prefer-dist --optimize-autoloader --no-dev

COPY . /app

RUN box validate

RUN box compile

FROM php:8.5-cli@sha256:1954ff5cd21f222c992b79d25e403b2600cec829678d5bb7076883f3a44c0d6e

# git is required because the tool shells out to the git binary; openssh-client
# enables pushing to SSH remotes such as git@github.com:org/repo.git.
# hadolint ignore=DL3008
RUN apt-get update && \
    apt-get install --no-install-recommends -y git openssh-client && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# The container operates on repositories bind-mounted from the host at runtime -
# a source and a destination whose paths are chosen by the caller and unknown at
# build time - while running as root. Trust all directories so git does not
# reject these host-owned mounts for dubious ownership; the container is
# single-purpose and ephemeral, so the wildcard is an acceptable trust boundary.
RUN git config --system --add safe.directory '*'

WORKDIR /app

COPY --from=builder /app/.build/git-artifact /usr/local/bin/git-artifact

RUN chmod +x /usr/local/bin/git-artifact

ENTRYPOINT ["/usr/local/bin/git-artifact"]
