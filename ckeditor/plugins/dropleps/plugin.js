/**
 *	@module			ckeditor
 *	@version		see info.php of this module
 *	@authors		Dietrich Roland Pehlke, erpe
 *	@copyright		2012 - 2015 Dietrich Roland Pehlke, erpe
 *	@license		GNU General Public License
 *	@license terms	see info.php of this module
 *
 */

// Register the related commands.
CKEDITOR.plugins.add('dropleps',
{
    lang : ['en','de','nl'],
    init: function(editor)
    {
        editor.addCommand('droplepsDlg', new CKEDITOR.dialogCommand('droplepsDlg'));
        editor.ui.addButton('dropleps',
            {
                label: editor.lang.dropleps.btn,
                command: 'droplepsDlg',
                icon: this.path + 'images/dropleps.gif'
            });
        CKEDITOR.dialog.add('droplepsDlg', this.path + 'dialogs/dropleps.php');
    }
});