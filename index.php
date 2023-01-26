<?php include './vendor/autoload.php' ?>
<?php include './includes/header.php' ?>
<?php include './functions/helpers.php' ?>
<?php include './functions/database.php' ?>


<?php
registerExceptionHandler(); //for whoops

$connection = dbConnect('root','','todo_v2');


$withTrashed = false;

if(isset($_POST['todo']))
{
    addTodo($connection, $_POST['todo']);
}
if(isset($_POST['delete']))
{
    deleteTodo($connection, $_POST['id']);
}
if(isset($_POST['check']))
{
    checkTodo($connection, $_POST['id']);
}
if(isset($_POST['uncheck']))
{
    unCheckTodo($connection, $_POST['id']);
}

?>
<div class="text-3xl text-center font-bold mb-3 uppercase">Todo List</div>
        <div>
            <form action="#" method="POST"class="flex justify-center">
                <input type="text" name="todo" placeholder="Enter Todo" class="text-xl text-orange-800 placeholder-orange-400 py-2 px-5 bg-orange-100 rounded-l-full outline-orange-300">
                <button type="submit" class="text-xl text-orange-100 placeholder-orange-400 py-2 pr-5 pl-4 bg-orange-500 rounded-r-full">
                    <?= svg('plus')?>
                </button>
            </form>
        </div>
            <div class="bg-gray-100 mt-5 p-5 rounded-xl shadow-lg text-gray-700">
            <h1 class="font-bold text-xl italic block mb-0 leading-none">Todo's</h1>
            <small class="block mb-5 mt-0 text-xs text-gray-500"><?=getNotDoneTodos($connection)?> Todos pending, <?= getDoneTodos($connection) ?> Completed.</small>
            <div class="max-h-80 overflow-y-auto">
                <table class="table w-full">
                    <thead>
                        <tr>
                            <th class="text-center px-1 py-2 bg-orange-500 text-orange-100 rounded-tl-xl">#</th>
                            <th class="text-left px-1 py-2 bg-orange-500 text-orange-100">Details</th>
                            <th class=" px-1 py-2 bg-orange-500 text-orange-100 rounded-tr-xl">Action</th>
                        </tr>
                    </thead>
                    <tbody >
                        <?php if (getTodoCount($connection) ===0) : ?>
                            <tr class="odd:bg-orange-100 even:bg-orange-50">
                                <td class="text-center  px-1 py-2 text-orange-800" colspan="3">No Todos found. Add a few to begin.</td>
                            </tr>
                        <?php else : ?>
                            <?php foreach (getTodos($connection) as $key => $todo ) : ?>
                                <?php if($todo['done']==0): ?>
                                    <tr class="odd:bg-orange-100 even:bg-orange-50">
                                <?php elseif($todo['done']==1): ?>
                                    <tr class="odd:bg-green-100 even:bg-green-50">
                                <?php endif ?>
                                <td class="text-center  px-1 py-2 text-orange-800"><?=$key+1?></td>
                                <td class=" px-1 py-2 text-orange-800<?=getLine($todo)?>"><?= $todo['text']?></td>
                                <td class="text-center  px-1 py-2 text-orange-800 flex gap-3 justify-start">
                                    <form method="post">
                                        <input type="hidden" name="id" value="<?=$todo['id']?>">
                                        <?php if($todo['done'] == 0): ?>
                                        <button class="text-orange-600" name="check">
                                            <?= svg('check') ?>
                                        </button>
                                        <?php endif ?>

                                        <?php if($todo['done'] == 1): ?>
                                        <button class="text-orange-600" name="uncheck">
                                            <?= svg('cross') ?>
                                        </button>
                                        <?php endif ?>

                                        <button class="text-orange-600" name="delete">
                                            <?= svg('trash') ?>
                                        </button>

                                    </form>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>
        </div>

<?php include './includes/footer.php' ?>
