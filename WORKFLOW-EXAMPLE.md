# Git Workflow Example

This file demonstrates the feature branch workflow in action.

## What this represents:
- Created from `dev` branch
- Contains isolated changes for a specific feature
- Will be merged back to `dev` when complete
- Keeps development organized and traceable

## Commands used:
```bash
git checkout dev
git checkout -b feature/git-workflow-example
# Make changes...
git add .
git commit -m "Add workflow example"
git checkout dev
git merge feature/git-workflow-example
git branch -d feature/git-workflow-example
```

This file can be safely deleted after the workflow demonstration.
