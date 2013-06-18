<?php
/**
 * File Language
 *
 * model =
 * model prop Status =
 * model prop Status validate key1 =
 * model prop Status validate key2 =
 * model prop Status option cold =
 * model prop Status option hot =
 *
 * controller Zero_Users_Grid =
 * controller Zero_Users_Grid action name1 =
 * controller Zero_Users_Grid action name2 =
 * controller Zero_Users_Grid message name1 =
 * controller Zero_Users_Grid message name2 =
 *
 * 'translation Key' => 'Translation Value'
 */
return [
    'controller Zero_System_GridService' => 'Development and maintenance of the system',
    'controller Zero_System_GridService action Engine_Modules_DB' => 'Engineering model',
    'controller Zero_System_GridService action Cache_Reset' => 'Full reset cache',
    'controller Zero_System_GridService action Session_Reset' => 'Reset all sessions',
    'controller Zero_System_GridService message Session_Reset' => 'Sessions reset',
    'controller Zero_System_GridService message Cache_Reset' => 'Сache reset success',
    'controller Zero_System_GridService message Engine_Modules_DB' => 'The module is created and / or initialized',
    'controller Zero_System_GridService message Error_Engine_Modules_DB' => 'Error creating the module',
    'controller Zero_System_GridService message Error_NotParam' => 'Parameters query   are not set',
    'controller Zero_System_FileEdit' => 'The File Manager. Edit file',
    'controller Zero_System_FileEdit action FileSave' => 'save',
    'controller Zero_System_FileEdit message FileSave' => 'File is saved',
    'controller Zero_System_FileManager' => 'The File Manager',
    'controller Zero_System_FileManager action FolderGo' => 'Transition into folders',
    'controller Zero_System_FileManager action FolderRemove' => 'Delete folders',
    'controller Zero_System_FileManager action FileRemove' => 'Deleting a file',
    'controller Zero_System_FileManager action FileDownLoad' => 'output the users to the file',
    'controller Zero_System_FileManager action FileUpload' => 'download the file to the server',
    'controller Zero_System_FileManager action FolderAdd' => 'create a folder',
    'controller Zero_System_FileManager action EditFile' => 'changes to the file',
    'controller Zero_System_FileManager message FolderAdd' => 'folder is created',
    'controller Zero_System_FileManager message Error_FolderAdd' => 'Error creating folder',
    'controller Zero_System_FileManager message FileUpload' => 'Uploaded',
    'controller Zero_System_FileManager message Error_FileUpload' => 'Error loading file',
    'controller Zero_System_FileManager message FileRemove' => 'File is deleted',
    'controller Zero_System_FileManager message Error_FileRemove' => 'Failed to delete file',
    'controller Zero_System_FileManager message FolderRemove' => 'Folder is deleted',
    'controller Zero_System_FileManager message Error_FolderRemove' => 'Failed to delete the folder',
    'controller Zero_System_GridModules' => 'Module Management',
    'controller Zero_System_GridModules action Up' => 'update module',
    'controller Zero_System_GridModules action Set' => 'installing the module',
    'controller Zero_System_GridModules action Rem' => 'removal of a module',
    'controller Zero_System_GridModules message Rem' => 'Module is removed',
    'controller Zero_System_GridModules message Failure to remove the module from the database' => 'Failure to remove the module from the database',
    'controller Zero_System_GridModules message Error installing the module in the database' => 'Error installing the module in the database',
    'controller Zero_System_GridModules message Module already installed' => 'The module is installed or not require installation',
    'controller Zero_System_GridModules message Module already removed' => 'The module has already been removed or does not require removal',
    'controller Zero_System_GridModules message Application installed' => 'The application is installed',
    'controller Zero_System_GridModules message Application removed' => 'Appendix removed',
    'controller Zero_System_GridModules message Module installed' => 'The module is installed',
    'controller Zero_System_GridModules message Module removed' => 'Module is removed',
    'controller Zero_System_GridModules message Installer Not Found' => 'The installer is not found',
    'controller Zero_System_GridModules message DeInstaller Not Found' => 'The uninstaller not found',
    'translation engine' => 'Engineering model',
    'translation model factory' => 'Factoring / Refactor models. <br> If the model is not available, it is created. <br> Specifies the name of the module (orientation on the database). <br>',
    'translation Key' => 'Translation Value',
];