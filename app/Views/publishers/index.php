<?php

$tableBody = "
            <form method='post' action='/publishers/create' id='publisherForm'>
                <button type='submit' name='btn-plus' class='btn-plus' title='Új'>
                    <i class='fa fa-plus plus'></i>&nbsp;Új
                </button>
            </form><div class='publishersContainer'>";
foreach ($publishers as $publisher) {
    $tableBody .= <<<HTML
            <table>
                <tr>
                    <td>{$publisher->publisher}</td>
                    <td>
                        <form method='post' action='/publishers/edit'>
                            <input type='hidden' name='id' value='{$publisher->id}'>
                            <button type='submit' name='btn-edit' title='Módosít'><i class='fa fa-edit'></i></button>
                        </form>
                    </td>
                    <td>
                        <form method='post' action='/publishers'>
                            <input type='hidden' name='id' value='{$publisher->id}'>
                            <input type='hidden' name='_method' value='DELETE'>
                            <button type='submit' name='btn-del' title='Töröl'><i class='fa fa-trash trash'></i></button>
                        </form>
                    </td>
                </tr>
            </table>
            HTML;
}
echo $tableBody . '</div>';
