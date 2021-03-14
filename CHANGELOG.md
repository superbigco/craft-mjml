# MJML Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) and this project adheres to [Semantic Versioning](http://semver.org/).

## 1.0.5 - 2020-12-18
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
