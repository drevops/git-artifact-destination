# git-artifact-destination
Deployment destination test repository for [git-artifact](https://github.com/drevops/git-artifact) project

Visit [Branches](https://github.com/drevops/git-artifact-destination/branches) tab to see all artifact branches pushed into this repo.

## `force-push` mode

- `mode--force-push--circleci--[BRANCH]` branch is where artifact is pushed from [CircleCI](https://github.com/drevops/git-artifact/blob/main/.circleci/config.yml#L66) when ran in `force-push` mode.
- `mode--force-push--gha--[BRANCH]` branch is where artifact is pushed from [Github Actions](https://github.com/drevops/git-artifact/blob/main/.github/workflows/test-php.yml#L70) when ran in `force-push` mode.

Note `test-file--force-push--[CI PROVIDER]--[BRANCH].txt` file content changes with each push to this branch. This is because the contents of this branch is being constantly overridden by the new deployments.

## `branch` mode

- `mode--branch--circleci--[BRANCH]-*` branches is where artifact is pushed from [CircleCI](https://github.com/drevops/git-artifact/blob/main/.circleci/config.yml#L66) when ran in `branch` mode 
- `mode--branch--gha--[BRANCH]-*` branches is where artifact is pushed from [Github Actions](https://github.com/drevops/git-artifact/blob/main/.github/workflows/test-php.yml#L163) when ran in `branch` mode 

Note `test-file--branch--[CI PROVIDER]--[BRANCH].txt` file content not changes with each push. This is because the contents of the artifact is pushed to the **new** branch on each deployment.
