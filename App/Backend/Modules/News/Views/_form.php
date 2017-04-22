<form action="" method="post">
    <p>
        <?= $form ?>

        <?php
        if(isset($news) && !$news->isNew()) {
        ?>
            <input type="hidden" name="id" value="<?= $news['id'] ?>">
            <input type="submit" name="name" value="Modifier">
        <?php
        } else {
        ?>
            <input type="submit" value="Ajouter">
        <?php
        }
        ?>
    </p>
</form>
