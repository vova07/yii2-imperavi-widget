# Upgrade guide from version `1.*` to `2.*`

- Replace any `UploadAction` entry with new `UploadFileAction` instance.
- Replace `GetAction` with one of appropriate new actions `GetFilesActions` for file uploading or `GetImagesActions` for image uploading.

**Please read the documentation to get in touch with new futures**
