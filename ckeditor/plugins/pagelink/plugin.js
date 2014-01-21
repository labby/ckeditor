/**
 *	@module			ckeditor
 *	@version		see info.php of this module
 *	@authors		Dietrich Roland Pehlke, erpe
 *	@copyright	2012 - 2014 Dietrich Roland Pehlke, erpe
 *	@license		GNU General Public License
 *	@license terms	see info.php of this module
 *
 */


// Register the related commands.
CKEDITOR.plugins.add('pagelink',
{
    lang : ['en','de','nl','ru'],
    init: function(editor)
    {
        var pluginName = 'pagelink';
        editor.addCommand('pagelinkDlg', new CKEDITOR.dialogCommand('pagelinkDlg'));
        editor.ui.addButton('pagelink',
            {
                label: editor.lang.pagelink.btn,
                command: 'pagelinkDlg',
                icon: this.path + 'images/pagelink.gif'
            });
        CKEDITOR.dialog.add('pagelinkDlg', this.path + 'dialogs/pagelink.php');
    }
});