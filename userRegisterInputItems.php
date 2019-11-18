<?php

require_once __DIR__ . '/Utils/function.php';

$disabled = $enabledInputItem ?: 'disabled';

// 表示する値の設定
$email = empty($email) ? '' : $email;
$password = empty($password) ? '' : $password;
$passwordConfirm = empty($passwordConfirm) ? '' : $passwordConfirm;
$nickname = empty($nickname) ? '' : $nickname;

?>

<div class="main-content-input">
    <form action="#" method="post">
        <ul class="input-list">
            <li class="input-list-item">
                <label for="email">
                    <span class="input-list-item__label">Eメールアドレス</span>
                    <?php if (!empty($errorInfo['email'])) { ?>
                        <span class="font--error"><?= h($errorInfo['email']) ?></span>
                    <?php } ?>
                </label>
                <input class="input-list-item__text" type="email" name="email" value="<?= h($email) ?>" <?= $disabled ?>>
                <?php if (!$enabledInputItem) { ?>
                    <input type="hidden" name="email" value="<?= h($email) ?>">
                <?php } ?>
            </li>
            <li class="input-list-item">
                <label for="password">
                    <span class="input-list-item__label">パスワード</span>
                    <?php if (!empty($errorInfo['password'])) { ?>
                        <span class=" font--error"><?= h($errorInfo['password']) ?></span>
                    <?php } ?>
                </label>
                <input class="input-list-item__text" type="password" name="password" value="<?= h($password) ?>" placeholder="6文字以上で入力してください" <?= $disabled ?>>
                <?php if (!$enabledInputItem) { ?>
                    <input type="hidden" name="password" value="<?= h($password) ?>">
                <?php } ?>
            </li>
            <li class="input-list-item">
                <label for="passwordConfirm">
                    <span class="input-list-item__label">パスワード確認</span>
                    <?php if (!empty($errorInfo['passwordConfirm'])) { ?>
                        <span class=" font--error"><?= h($errorInfo['passwordConfirm']) ?></span>
                    <?php } ?>
                </label>
                <input class="input-list-item__text" type="password" name="passwordConfirm" value="<?= h($passwordConfirm) ?>" <?= $disabled ?>>
                <?php if (!$enabledInputItem) { ?>
                    <input type="hidden" name="passwordConfirm" value="<?= h($passwordConfirm) ?>">
                <?php } ?>
            </li>
            <li class="input-list-item">
                <label for="nickname">
                    <span class="input-list-item__label">ニックネーム</span>
                    <?php if (!empty($errorInfo['nickname'])) { ?>
                        <span class=" font--error"><?= h($errorInfo['nickname']) ?></span>
                    <?php } ?>
                </label>
                <input class="input-list-item__text" type="text" name="nickname" value="<?= h($nickname) ?>" <?= $disabled ?>>
                <?php if (!$enabledInputItem) { ?>
                    <input type="hidden" name="nickname" value="<?= h($nickname) ?>">
                <?php } ?>
            </li>
            <li class="input-list-item">
                <input class="input-list-item__submit btn-opacity--hover" type="submit" value="<?= h($submitText) ?>">
            </li>
        </ul>
    </form>
</div>