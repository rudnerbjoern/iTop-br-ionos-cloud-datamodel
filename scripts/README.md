# Release preparation

On a `release/X.Y.Z` branch, run `php scripts/prepare-release.php X.Y.Z` from the repository root. This updates the descriptor date and versions and the IONOS module registration. The compatibility requirement for the external Cloud base is managed independently. Set up the local schemas before running the command. Review the changes before opening the release pull request.

Merging a release pull request from this repository into `main` publishes the version tag, runtime ZIP and SHA-256 checksum after the required PR checks pass. To recover an interrupted release, retry its workflow run for the same commit.
