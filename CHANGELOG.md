# Changelog

All notable changes to `yii2-imperavi-widget` will be documented in this file.

## 2.0.11

### Changed
- Fixed `table.js` bug by replacing `size()` with `length`. (bocceli)

## 2.0.10

### Changed
- Added `Spanish` translation for `widget` messages. (jcvalerio) 

## 2.0.9

### Changed
- Completed `Ukrainian` translation. (Sensetivity)

## 2.0.8

### Changed
- Add `Slovak` language support. (snickom)

## 2.0.7

### Changed
- Fix the issue #130 add a custom clips throw settings. (bscheshirwork) 

## 2.0.6

### Changed
- Prevent addition of wrong construction `"=""` into various tags. (solks)

## 2.0.5

### Changed
- Fix Ukrainian code inside of `uk.js` file. (do6po)

## 2.0.4

### Changed
- Fix for all webkit-based browsers. (bzz445)
- Fix the file name for Ukrainian language. (vova07)

## 2.0.3

### Changed
- Minor documentation fixes. (vova07)

## 2.0.2

### Changed
- Minor documentation fixes. (vova07)

## 2.0.1

### Changed
- Fix the issue #112 related with multiple widget's instances assets registration. (vova07)
- Adjust the documentation. (vova07)
- Adjust the widget's actions documentation blocks. (vova07)
- Refactor javascript custom plugins to use official registration approach. (vova07)

## 2.0.0

### Changed
- Fix numerous bugs and issue related with the redactor wrapper. (vova07)
- Rewrite tests and fix travis suites. (vova07)
- Replace `UploadAction` with `UploadFileAction`. (vova07)
- Replace `GetAction` with two new actions: `GetFilesAction` and `GetImagesAction`. (vova07)
- Add delete files functionality to `file manager`. (vova07)  
- Add delete images functionality to `image manager`. (vova07)
- Add `translit` support to `UploadFileAction`. (vova07)
- Add better error messages for unsuccessful requests on images and files upload. (vova07)
- Remove `FileHelper` in favor of `BaseFilehelper`. (vova07)
- Add localization for `image manager` and `file manager`. (vova07)
- Add localization for `fullscreen` plugin. (vova07)  
