# robo-git-artefact-destination
Destination repository for [git-artifact](https://github.com/drevops/git-artifact) project

Visit [Branches](https://github.com/drevops/git-artifact-destination/branches) tab to see all artifact branches pushed into this repo.

## `force-push` mode

- `mode-force-push-circle` branch is where artifact is pushed from CircleCI when ran in `force-push` mode.
- `mode-force-push-github` branch is where artifact is pushed from Github Actions when ran in `force-push` mode.

Note `test-file-*-[timestamp].txt` file name changes with each push to this branch. This is because the contents of this branch is being constantly overridden by the new pushes.

## `branch` mode

- `mode-branch-circleci-*` branches is where artifact is pushed from CirclecCI when ran in `branch` mode 
- `mode-branch-github-*` branches is where artifact is pushed from Github Actions when ran in `branch` mode 

Note `test-file-*-[timestamp].txt` file name is preserved in each branch with new pushes. This is because the contents of the artifact is pushed to the **new** branch on each build.
