<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\CodageController;
use App\Http\Controllers\PesanController;


Route::get('/', function () {
    return view('home');
});

// *** USER *** //
Route::group(['middleware' => ['auth','cekrole:user']], function(){
    // *** ARTICLE *** //
    Route::get('user/article', [CodageController::class, 'article'])->name('codage.article');
    Route::get('user/article/categories/{tag}', [CodageController::class, 'categories_article'])->name('codage.article.categories');

    // *** GAME QUIZ *** //
    Route::get('user/gameQuiz', [CodageController::class, 'gameQuiz'])->name('codage.gameQuiz');
    Route::get('user/gameQuiz/quiz', [CodageController::class, 'quiz'])->name('codage.quiz');
    Route::get('user/gameQuiz/quizplay/{id}', [CodageController::class, 'quizplay']);
    Route::get('/quizend/{perc}',['uses' => 'App\Http\Controllers\CodageController@quizend']);

    // *** STORE *** //
    Route::get('user/store', [CodageController::class, 'store'])->name('codage.store');

    Route::group(['as' => 'user/store/'], function (){
        Route::get('pesan/{id}', [PesanController::class, 'index'])->name('store.index');
        Route::post('pesan/{id}', [PesanController::class, 'pesan'])->name('store.pesan');
        Route::get('check-out', [PesanController::class, 'check_out'])->name('store.check');
        Route::delete('check-out/{id}', [PesanController::class, 'delete'])->name('store.delete');

        Route::get('konfirmasi-checkout', [PesanController::class, 'konfirmasi'])->name('store.confirm');
    });

    // *** LOGOUT *** //
    Route::get('user/logout', LogoutController::class)->name('logout');
});

Route::group(['middleware' => ['auth','cekrole:admin,user']], function(){
    // *** DASHBOARD *** //
    Route::get('dashboard', [CodageController::class, 'index'])->name('codage.index');

});

Route::group(['middleware' => ['auth','cekrole:admin']], function(){
    // *** ARTICLE *** //
    Route::resource('admin/article', PostController::class);

    // *** GAME QUIZ *** //
    // START
    Route::group(['as' => 'admin/quiz/'], function () {
        Route::get('quizzes', [QuizController::class, 'index'])->name('quiz.index');
        Route::post('edit-content/{id}', [QuizController::class, 'editContent'])->name('quiz.update');
        Route::get('admin-quizz', [QuizController::class, 'adminquizz'])->name('quiz.admin');

        Route::get('category/{id}', [QuizController::class, 'Category'])->name('quiz.category');
        Route::get('edit-category/{id}',[QuizController::class, 'editCategory'])->name('quiz.edit.category');
        Route::post('edit-category-action/{id}', [QuizController::class, 'editCategoryAction'])->name('quiz.action.category');

        Route::get('delete-category/{id}', [QuizController::class, 'deleteCategory'])->name('quiz.delete.category');
        Route::get('create-category', [QuizController::class, 'createCategory'])->name('quiz.create.category');
        Route::post('create-category-action', [QuizController::class, 'storeCategory'])->name('quiz.store.category');

        Route::get('question/{id}', [QuizController::class, 'Question'])->name('quiz.question');
        Route::get('create-question/{id}', [QuizController::class, 'createQuestion'])->name('quiz.create.question');
        Route::post('create-question-action/{id}', [QuizController::class, 'storeQuestion'])->name('quiz.store.question');

        Route::get('edit-question/{id}', [QuizController::class, 'editQuestion'])->name('quiz.edit.question');
        Route::post('edit-question-action/{cid}/{qid}', [QuizController::class, 'editQuestionAction'])->name('quiz.action.question');
        Route::get('delete-question/{cid}/{qid}', [QuizController::class, 'deleteQuestion'])->name('quiz.delete.question');
    });
    // END

    // *** STORE *** //
    Route::resource('admin/store', ProductController::class);
});

require __DIR__.'/auth.php';
