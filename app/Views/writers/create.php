<?php

echo <<<HTML
        <form method='post' action='/writers' enctype="multipart/form-data">
            <fieldset>
                <table>
                    <tr>
                        <td><label for="writer">Író</label></td>
                        <td><input type="text" name="writer" id="writer"></td>
                    </tr>
                    <tr>
                        <td><label for="bio">Bio</label></td>
                        <td><textarea type="text" name="bio" id="bio" class="bio"></textarea></td>
                    </tr>
                </table>
                <hr>
                <button type="submit" name="btn-save" class="btn-save">
                    <i class="fa fa-save"></i>&nbsp;Mentés
                </button>
                <a href="/writers"><i class="fa fa-cancel">                    
                    </i>&nbsp;Mégse
                </a>
            </fieldset>
        </form>
    HTML;