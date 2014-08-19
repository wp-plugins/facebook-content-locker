<table cellspacing="0" class="wp-list-table widefat fixed posts">
    <thead>
        <tr>
            <th style="" class="manage-column column-cb check-column" id="cb" scope="col">
            </th>
            <th style="" class="manage-column column-title sortable desc" id="title" scope="col">
                <span>Title</span>
            </th>
            <th style="" class="manage-column column-author sortable desc" id="author" scope="col">
                <span>Show on home page</span>
            </th>
            <th style="" class="manage-column column-categories" id="categories" scope="col">
                Show on all pages
            </th>
            <th style="" class="manage-column column-tags" id="tags" scope="col">
                Show on all posts
            </th>
            <th style="" class="manage-column column-date sortable asc" id="date" scope="col">
                <span>Actions </span>
            </th>	
        </tr>
    </thead>

    <tfoot>
        <tr>
            <th style="" class="manage-column column-cb check-column" id="cb" scope="col">
            </th>
            <th style="" class="manage-column column-title sortable desc" id="title" scope="col">
                <span>Name</span>
            </th>
            <th style="" class="manage-column column-author sortable desc" id="author" scope="col">
                <span>Show on home page</span>
            </th>
            <th style="" class="manage-column column-categories" id="categories" scope="col">
                Show on all pages
            </th>
            <th style="" class="manage-column column-tags" id="tags" scope="col">
                Show on all posts
            </th>
            <th style="" class="manage-column column-date sortable asc" id="date" scope="col">
                <span>Actions </span>
            </th>	
        </tr>
    </tfoot>

    <tbody id="the-list">
        <?php
        foreach ($res as $r) {
            ?>
            <tr valign="top" class="post-1 post type-post status-publish format-standard hentry category-non-classe alternate iedit author-self" id="post-1">
                <th class="check-column" scope="row"></th>
                <td class="post-title page-title column-title">
                    <strong>
                        <?php echo $r->title;?>
                    </strong>
                </td>			
                <td class="author column-author">
                        <?php if ($r->showhomepage == 1) echo "Yes"; else echo "No";?>
                </td>
                <td class="categories column-categories">
                        <?php if ($r->showallpages == 1) echo "Yes"; else echo "No";?>
                </td>
                <td class="tags column-tags">
                        <?php if ($r->showallposts == 1) echo "Yes"; else echo "No";?>
                </td>
                <td class="date column-date">
                    <a href="admin.php?page=wpliketounlockmanage&action=edit&id_like=<?php echo $r->id_like;?>">
                        Edit
                    </a> - 
                    <a href="admin.php?page=wpliketounlockmanage&action=delete&id_like=<?php echo $r->id_like;?>">
                        Delete
                    </a>
                </td>
            </tr>
            <?php
        }
        ?>
    </tbody>
</table>