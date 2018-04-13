/**
 * Order Notes plugin for Craft CMS
 *
 * Order Notes JS
 *
 * @author    Superbig
 * @copyright Copyright (c) 2018 Superbig
 * @link      https://superbig.co
 * @package   OrderNotes
 * @since     2.0.0
 */
class OrderNotes {
    constructor(id, notes) {
        this._orderId = id;
        this._notes = notes;
        this._preventSubmit = true;
        this._isSubmitting = false;
        this._hasNote = false;

        this._insertSection();
        this._insertNotes();

        $(window).on('beforeunload', $.proxy(this._checkNoteOnUnload, this));
    }

    _insertSection() {
        const $main = $('#orderHistoryTab');
        const $pane = $('<div class="pane"><h3>Order notes</h3></div>');
        const $form = $('<form></form>');
        const $button = $('<footer class="footer"><div class="btngroup right"><input type="submit" class="btn submit disabled" value="Submit" disabled></div></footer>');
        const $field = $('<div class="field"><div class="heading"><label class="hidden">Message</label></div><div class="input ltr"><textarea class="text fullwidth" rows="2" cols="50" name="message"></textarea></div></div>');
        const $notifyField = $('<div class="input ltr"><div><input type="checkbox" value="test1" id="fields-checkbox942945807" class="checkbox" name="fields[checkboxTest][]"><label for="fields-checkbox942945807">Notify customer</label></div></div>');
        const $notes = $('<div class="ordernotes-notes"></div>');

        this._$field = $field.find('textarea');
        this._$notifyField = $notifyField.find('input');
        this._$button = $button.find('input');
        this._$notes = $notes;

        this._$button.removeAttr('disabled');

        $form
            .on('submit', $.proxy(this._onSubmit, this))
            .on('input', $.proxy(this._onInput, this))

        $form
            .append($field)
            .append($notifyField)
            .append($button);
        $pane
            .append($notes)
            .append('<hr />')
            .append('<h3>Add note</h3>')
            .append($form)
        $main.append($pane);
    }

    _insertNotes() {
        if (this._notes === null) {
            this._$notes.append('<p>No notes</p>');

            return;
        }

        let noteOutput = '';

        this._notes.map(note => {
            noteOutput += '<div class="ordernotes-note"><p>' + note.message + '</p><p class="light">' + note.username + ' - ' + note.date + '</p>';

            if (note.notify) {
                noteOutput += '<p class="light">Customer was notified</p>';
            }

            noteOutput += '</div>';
        });

        this._$notes.html(noteOutput);
    }

    _onInput() {
        const text = this._$field.val();
        this._preventSubmit = text.trim().length === 0;
        this._hasNote = !this._preventSubmit;

        this._$button.toggleClass('disabled', this._preventSubmit);

        if (this._preventSubmit) {
            this._$button.attr('disabled', 'disabled');
        } else {
            this._$button.removeAttr('disabled');
        }
    }

    _onSubmit(e) {
        e.preventDefault();

        if (this._preventSubmit) {
            return false;
        }

        this._isSubmitting = true;

        Craft.postActionRequest('order-notes/default/add-note', this._getData(), response => {
            Craft.cp.displayNotice('Added note, reloading order.');

            setTimeout(_ => {
                window.location.reload();
            }, 250);
        });
    }

    _getData() {
        return {
            message: this._$field.val(),
            notify: this._$notifyField.is(':checked'),
            orderId: this._orderId
        }
    }

    _checkNoteOnUnload(e) {
        if (this._isSubmitting || !this._hasNote) {
            return undefined;
        }

        const message = 'It looks like you were writing a order note.'
            + 'If you leave before saving your note, your changes will be lost.';

        if (e)
        {
            e.originalEvent.returnValue = message;
        }
        else
        {
            window.event.returnValue = message;
        }

        return message;
    }
}