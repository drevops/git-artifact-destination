# robo-git-artefact-destination
Destination repository for [robo-git-artefact](https://github.com/integratedexperts/robo-git-artefact) project


# Wait, don't leave. This repo is NOT empty!

Visit [Branches](https://github.com/integratedexperts/robo-git-artefact-destination/branches) tab to see all artefact branches pushed into this repo.

`mode-force-push` branch is where artefact is pushed when ran in `force-push` mode. 

Note `test-file-[timestamp].txt` file name changes with each push to this branch. This is because the contents of this branch is being constantly overridden by the new pushes.


`mode-branch-*` branches is where artefact is pushed when ran in `branch` mode.


Note `test-file-[timestamp].txt` file name is preserved in each branch with new pushes. This is because the contents of the artefact is pushed to the **new** branch on each build.
