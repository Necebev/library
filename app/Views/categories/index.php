<?php

$tableBody = "
            <form method='post' action='/categories/create' id='categoriesForm'>
                <button type='submit' name='btn-plus' class='btn-plus' title='Új'>
                    <i class='fa fa-plus plus'></i>&nbsp;Új
                </button>
            </form><div class='categoriesContainer'>";
foreach ($categories as $category) {
    $tableBody .= <<<HTML
            <table>
                <tr>
                    <td>{$category->category}</td>
                    <td>
                        <form method='post' action='/categories/edit'>
                            <input type='hidden' name='id' value='{$category->id}'>
                            <button type='submit' name='btn-edit' title='Módosít'><i class='fa fa-edit'></i></button>
                        </form>
                    </td>
                    <td>
                        <form method='post' action='/categories'>
                            <input type='hidden' name='id' value='{$category->id}'>
                            <input type='hidden' name='_method' value='DELETE'>
                            <button type='submit' name='btn-del' title='Töröl'><i class='fa fa-trash trash'></i></button>
                        </form>
                    </td>
                </tr>
            </table>
            HTML;
}
echo $tableBody . '</div>';
