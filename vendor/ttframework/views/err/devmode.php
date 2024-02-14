<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Error</title>
</head>
<body>
    <div style="margin: 2%; width:100%">
        <?= isset($error['message']) ? $error['message'] : '';?>
    </div>
    <div style="padding: 1%">
        <code>Stack trace:</code>
    <?php if(isset($error['trace'])) : ?>
        <table style="width: 100%; border: 1px solid;" rules="all">
            <thead style="background-color: #ff9800">
                <td style="width:2.5%">#</td>
                <td>FilePath(Line)</td>
                <td>Class->Method</td>
            </thead>
            <tbody>
                <?php foreach ($error['trace'] as $key => $string) :
                if($string !== '') :
                $g = explode(' ', $string);?>
                <tr style="background-color: <?= ($key % 2) === 0 ? '#ffffff' : '#8080804d'?>">
                    <td>#<?=$key?></td>
                    <td><?=isset($g[1]) ? $g[1] : ''?></td>
                    <td><?=isset($g[2]) ? $g[2] : ''?></td>
                </tr>
                <?php endif; ?>
                <?php endforeach;?>
            </tbody>
        </table>
    <?php endif;?>
    </div>
</body>
</html>