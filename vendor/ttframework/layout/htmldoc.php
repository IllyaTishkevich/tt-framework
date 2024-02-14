<!DOCTYPE html>
<html lang="en">
<head>
    <?php foreach ($this->getSource() as $source) : ?>
<?=$source;?>
    <?php endforeach; ?>
<meta charset="UTF-8">
    <title><?=$this->getTitle()?></title>
</head>
<body>
    <?=$this->renderLayout() ?>
</body>
</html>