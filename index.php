<head>
    <link rel="stylesheet" type="text/css" href="folder/css.css">
</head>
<body>

<?php
require 'folder/blogpost.php';

require 'folder/tags.php';

$databases = new Database;

$post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

if(@$_POST['delete']){
    $delete_id = $_POST['delete_id'];
    $databases->query('DELETE FROM blog_post WHERE id = :id');
    $databases->bind(':id',$delete_id);
    $databases->execute();
}

if(@$post['update']){
    $id = $post['id'];
    $title = $post['title'];
    $body = $post['post'];
    $date = $post['date'];

    $databases->query('UPDATE blog_post SET title = :title, post = :post, date = :date WHERE id = :id');
    $databases->bind(':post',$body);
    $databases->bind(':id',$id);
    $databases->execute();
}

if(@$post['submit']) {
    $title = $post['title'];
    $body = $post['post'];
    $date = $post['date'];


    $databases->query('INSERT INTO blog_post(title, post, date) VALUES(:title, :post, :date)');
    $databases->bind(':title', $title);
    $databases->bind(':post', $body);
    $databases->bind(':date', $date);
    $databases->execute();
    if ($databases->lastInsertId()) {
        echo '<p>Post Added!</p>';
    }
}

$Tags = new Tags();
$Tags->query('SELECT * FROM blog_post');
$rows = $Tags->resultset()

?>

<div class="form">
<h1>Add Posts</h1>
<form method="post" action="<?php $_SERVER['PHP_SELF']; ?>">

    <label>Post Id</label><br />
    <input type="text" name="id" placeholder="Specify ID" required/><br />

    <label>Post Title</label><br />
    <input type="text" name="title" placeholder="Add a Title..." required/><br /><br />
    <label>Post Date</label><br />
    <input type="date" name="date" required/><br /><br />
    <label>Post Body</label><br />
    <textarea name="post"></textarea><br /><br />

    <input type="submit" name="submit" value="Submit" />
</form>
</div>
<h1>Posts</h1>
<div>
    <?php foreach($rows as $row) : ?>
        <div class="post">
            <h3>
                <?php echo $row['title'];?>
            </h3>
            <p>
                <?php echo $row ['post']; ?>
            </p>
            </br>
            <span class="footer">
                <?php echo 'Date Created: '.$row['date']. '</br>'; ?>
                <p>Tags: <?php echo $row['tags']; ?></p>
            </span>
            <form method="post" action=""<?php $_SERVER['PHP_SELF']; ?>">
            <input type="hidden" name="delete_id" value="<?php echo $row['id'] ?>">
            <p class="delete"><input type="submit" name="delete" value="Delete" /></p>
            </form>

        </div>
    <?php endforeach; ?>
</div>

</body>