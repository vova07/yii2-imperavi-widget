# Upgrade guide from version `1.*` to `2.*`

- Replace any `UploadAction` entry with new `UploadFileAction` instance.
- Replace `GetAction` with one of appropriate new actions `GetFilesAction` for file uploading or `GetImagesAction` for image uploading.

**Please read the documentation to get in touch with new futures**
