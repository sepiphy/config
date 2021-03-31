## v1.0.0 (2021-03-31)

### Added

- Added `Sepiphy\Config\LoaderInterface` interface instead of depending on `sepiphy\contracts` package.
- Added `Sepiphy\Config\ConfigInterface` interface instead of depending on `sepiphy\contracts` package.

### Changed

- `Sepiphy\Config\Config` loads both php and yml files of a directory by default, no need to care about loaders anymore.