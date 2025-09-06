
# Team Collaboration Guide (Git Workflow)

## 📄 `COLLABORATION.md`

This document describes how we collaborate as a team using Git and GitHub.

---

### Table of Contents
- [Branching](#branching)
   - [Strategy](#strategy)
   - [Example Feature branches](#example-feature-branches)
- [Commit Message Conventions](#git-commit-naming-conventions-guide)
   - [Common Commit Types](#common-commit-types)
   - [Optional Scope Usage](#optional-scope-usage)
   - [Commit Message Guidelines](#commit-message-guidelines)
- [Collaboration Workflow](#collaboration-workflow)
- [Guidelines](#guidelines)
- [Project Issues/Feature Tracking Management (Kanban Board)](#project-issues-or-feature-tracking-management)

---

### Branching

#### Strategy

```
main                 → Stable production branch
dev                  → Integration branch (merged from all feature branches)
feature/feature-name → One feature per branch (e.g., `feature/checklist-ui`)
```

#### Example Feature Branches:

* `feature/checklist-ui`
* `feature/weather-alert`
* `feature/pwa-support`
 
---

### Git Commit Naming Conventions Guide

This guide ensures clear, consistent, and readable commit messages for your team. Use this structure for each commit:

```
<type>(optional-scope): short description
```

####  Common Commit Types

| Type       | Purpose                                             | Example                                               
| ---------- | --------------------------------------------------- | ----------------------------------------------------- 
| `feat`     | New feature                                         | `feat: add emergency contact print button`            
| `fix`      | Bug fix                                             | `fix: correct toggle bug on checklist`                
| `docs`     | Documentation updates                               | `docs: update README for hackathon`                   
| `style`    | Code Formatting/Added New UI                        | `style: reformat CSS for mobile view`                 
| `refactor` | Code restructure/refactor, no new feature or fix    | `refactor: simplify checklist update logic`           
| `perf`     | Performance improvement                             | `perf: improve load speed by optimizing loop`         
| `test`     | Adding or updating tests                            | `test: add unit test for rain alert`                  
| `build`    | Build system or dependency changes                  | `build: include weather api config`                   
| `ci`       | Continuous Integration configuration changes        | `ci: update GitHub Actions workflow`                  
| `chore`    | Maintenance tasks (file rename, code cleanup, etc.) | `chore: rename checklist.js to emergencyChecklist.js` 
| `revert`   | Revert a previous commit                            | `revert: "feat: add dark mode toggle"`                  
| `enhance`  | Code/Feature Improvements                           | `enhance: improve mobile responsiveness of checlist`                         

---

#### Optional Scope Usage

Use `(<scope>)` to specify what part of the app was affected:

```
<type>(scope): short message
```

**Examples:**
* `feat(ui): add dark mode support`
* `fix(api): handle missing response error`
* `chore(storage): rename key for localStorage`


#### Commit Message Guidelines

| Do ✅                        | Don't ❌                        |
| --------------------------- | ------------------------------ |
| Use present tense           | Use past tense                 |
| Be short and descriptive    | Be vague (`update`, `fix bug`) |
| Include a scope if helpful  | Use very long titles           |
| Use body for longer context | Put everything in the title    |

---

### Collaboration Workflow

1. **Start with the latest dev branch:**

   ```bash
   git checkout dev
   git pull origin dev
   ```

2. **Create your own feature branch:**

   ```bash
   git checkout -b feature/your-feature-name
   ```

3. **Commit your work using [Conventional Commits](#git-commit-naming-conventions-guide)**

   ```bash
   git add .
   git commit -m "feat(checklist): add reset functionality"
   git push origin feature/your-feature-name
   ```

4. **Create Pull Request into `dev` branch**

* Go to GitHub → Pull Requests → New Pull Request
* Set base branch to `dev`, compare with your feature branch
* Tag teammates for review

5. **Code Review**

* At least 1 team member should review the PR before merging
* Resolve conflicts if any

6. **Merge to dev only after approval**

   ```bash
   git checkout dev
   git pull origin dev
   git merge feature/your-feature-name
   ```
---

### Project Issues or Feature Tracking Management
We use the GitHub Projects Kanban Board to manage tasks:

[Open this project link if you are a repository collaborator](https://github.com/users/99lash/projects/9/)


| Status         | Description              
| ---------------|------------------------ 
|  Backlog     | Tasks hasn't been started        
|  Ready       | Tasks ready to be picked up         
|  In Progress | Actively being developed 
|  For Review  | Ready for code review    
|  Done        | Completed features       

### Issue Lifecycle:

* `To Do`: Newly created tasks
* `In Progress`: Assigned and actively worked on
* `Done`: Finished & merged to dev

### Tips:

* Link issues to PRs via `Fixes #issue_number`
* Create meaningful titles and descriptions
* Assign team members & labels

---

### Folder Guidelines

| Folder     | Purpose                                 |
| ---------- | --------------------------------------- |
| `/src/`    | HTML, JS, and CSS files                 |
| `/assets/` | Icons, images, logos                    |
| `/data/`   | Sample JSON, backup checklist templates |

---

### Final Notes

- Always pull latest `dev` before creating a new branch
- Push small commits regularly
- Follow commit message guidelines
- Use GitHub Discussions or Issues for questions
- Having a merge conflict? error pulling/fetching? Resolve together and communicate early.
- Committing directly to main / dev
- Leaving commented-out code or unused files
- Merging your own PR without review from a member or leader

Let’s collaborate efficiently and respectfully 🚀