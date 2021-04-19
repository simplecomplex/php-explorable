# Changelog

All notable changes to **simplecomplex/explorable** will be documented in this file,
using the [Keep a CHANGELOG](https://keepachangelog.com/) principles.


## [Unreleased]

### Changed
- Require PHP ^7.4 (or ^8.0).

### Fixed
- The property table should be class a var|constant, not an instance var
  (vs. simplecomplex/utils/explorable).
- Accommodate to changed behaviour of uninitialized instance vars when they are
  _typed_, they do not auto-initialize to null.
