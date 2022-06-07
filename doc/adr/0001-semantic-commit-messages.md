# Semantic Commit Messages

## Status

Approved

## Context

See how a minor change to your commit message style can make you a better programmer.

## Decision

Per each commit we will use the following format:

`<jira ticket number> | <type>(<scope>): <subject>`

### Example

```
GET-0000 | feat(client): add hat wobble
^--^          ^------------^
|                    |
|                    +-> Summary in present tense.
|
+-------> Type: chore, docs, feat, fix, refactor, style, or test.
```

More Examples:

- `feat`: (new feature for the user, not a new feature for build script)
- `fix`: (bug fix for the user, not a fix to a build script)
- `docs`: (changes to the documentation)
- `style`: (formatting, missing semi colons, etc; no production code change)
- `refactor`: (refactoring production code, eg. renaming a variable)
- `test`: (adding missing tests, refactoring tests; no production code change)
- `chore`: (updating grunt tasks etc; no production code change)

References:

- https://www.conventionalcommits.org/
- https://seesparkbox.com/foundry/semantic_commit_messages
- http://karma-runner.github.io/1.0/dev/git-commit-msg.html

## Consequences

Cleaner code, intelligible and machine ready.
