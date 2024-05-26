# MJML Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) and this project adheres to [Semantic Versioning](http://semver.org/).

## 2.0.1 - 2024-05-26
### Fixed
- Fixed issues with saving settings

## 2.0.0
### Added
- Initial Craft CMS 4 release

## 2.0.0-beta.1
### Added
- Added Craft 4 support

## 1.0.6 - 2021-03-17
### Added
- Added `include` method similar to Twig's `include` method so we can cache the MJML template once and then render the dynamic parts with Twig
- Added support for `<mj-include/>` tags for the CLI option
- Added `mjmlCliIncludesPath` config setting

### Changed
- Changed error handling to log more detailed error messages

## 1.0.5 - 2021-03-14
### Added
- Allow for optional CLI config settings (e.g. minify)

## 1.0.4 - 2020-04-01
### Fixed
- Always render template if in LivePreview mode ([#11](https://github.com/superbigco/craft-mjml/pull/11))

## 1.0.3 - 2020-03-24
### Added
- Added logging of CLI output when in devMode

## 1.0.2 - 2019-04-14
### Fixed
- Fixed a race condition where the wrong email contents sometimes was returned if two users triggered a render at the same time

## 1.0.1 - 2018-12-05
### Fixed
- Fixed bad references to `mjmlCliPath`

## 1.0.0 - 2018-12-05
### Added
- Initial release
