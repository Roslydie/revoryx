<?php

use App\Http\Controllers\TagController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\NewsletterController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [UserController::class, 'login']);
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::post('/reset-password', [ResetPasswordController::class, 'reset']);
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe']);
Route::get('/newsletter/unsubscribe/{id}', [NewsletterController::class, 'unsubscribe']);

Route::get('/public/blogs', [BlogController::class, 'allBlogs']);
Route::get('/public/blogs/recent', [BlogController::class, 'recentBlogs']);
Route::get('/public/blogs/{slug}', [BlogController::class, 'showBlog']);
Route::get('/public/projects', [ProjectController::class, 'allproject']);
Route::get('/public/projects/recent', [ProjectController::class, 'recentProjects']);
Route::get('/public/projects/{slug}', [ProjectController::class, 'showSlug']);
Route::get('/public/testimonials', [TestimonialController::class, 'published']);
Route::post('/contacts', [ContactController::class, 'store']);

Route::middleware('auth:sanctum')->group(function () {
	Route::post('/logout', [UserController::class, 'logout']);
	Route::get('/me', [UserController::class, 'getUser']);

	Route::get('/tags', [TagController::class, 'index']);
	Route::get('/tags/{id}', [TagController::class, 'show']);
	Route::post('/tags', [TagController::class, 'store']);
	Route::put('/tags/{id}', [TagController::class, 'edit']);
	Route::delete('/tags/{id}', [TagController::class, 'destroy']);

	Route::get('/categories', [CategoryController::class, 'index']);
	Route::get('/categories/{id}', [CategoryController::class, 'show']);
	Route::post('/categories', [CategoryController::class, 'store']);
	Route::put('/categories/{id}', [CategoryController::class, 'edite']);
	Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

	Route::get('/blogs', [BlogController::class, 'index']);
	Route::get('/blogs/{id}', [BlogController::class, 'show']);
	Route::post('/blogs', [BlogController::class, 'store']);
	Route::post('/blogs/{id}', [BlogController::class, 'edit']);
	Route::delete('/blogs/{id}', [BlogController::class, 'destroy']);
	Route::patch('/blogs/{id}/publish', [BlogController::class, 'publish']);
	Route::patch('/blogs/{id}/unpublish', [BlogController::class, 'unpublish']);

	Route::get('/projects', [ProjectController::class, 'index']);
	Route::get('/projects/{id}', [ProjectController::class, 'show']);
	Route::post('/projects', [ProjectController::class, 'store']);
	Route::post('/projects/{id}', [ProjectController::class, 'edit']);
	Route::delete('/projects/{id}', [ProjectController::class, 'destroy']);
	Route::patch('/projects/{id}/publish', [ProjectController::class, 'publish']);
	Route::patch('/projects/{id}/unpublish', [ProjectController::class, 'unpublish']);

	Route::get('/testimonials', [TestimonialController::class, 'index']);
	Route::get('/testimonials/{testimonial}', [TestimonialController::class, 'show']);
	Route::post('/testimonials', [TestimonialController::class, 'store']);
	Route::put('/testimonials/{testimonial}', [TestimonialController::class, 'update']);
	Route::delete('/testimonials/{testimonial}', [TestimonialController::class, 'destroy']);
	Route::patch('/testimonials/{testimonial}/publish', [TestimonialController::class, 'publish']);

	Route::get('/contacts', [ContactController::class, 'index']);
	Route::get('/contacts/{contact}', [ContactController::class, 'show']);
	Route::put('/contacts/{contact}', [ContactController::class, 'update']);
	Route::delete('/contacts/{contact}', [ContactController::class, 'destroy']);

	Route::get('/roles', [RoleController::class, 'index']);
	Route::get('/roles/{id}', [RoleController::class, 'show']);
	Route::post('/roles', [RoleController::class, 'store']);
	Route::put('/roles/{id}', [RoleController::class, 'update']);
	Route::delete('/roles/{id}', [RoleController::class, 'destroy']);

	Route::get('/users', [UserController::class, 'index']);
	Route::get('/users/{id}', [UserController::class, 'show']);
	Route::post('/users', [UserController::class, 'store']);
	Route::put('/users/{id}', [UserController::class, 'update']);
	Route::delete('/users/{id}', [UserController::class, 'destroy']);

	Route::get('/news', [NewsletterController::class, 'index']);
	Route::get('/news/{id}', [NewsletterController::class, 'show']);
	Route::post('/addnews', [NewsletterController::class, 'store']);
	Route::put('/updatenews/{id}', [NewsletterController::class, 'edit']);
	Route::delete('/deletenews/{id}', [NewsletterController::class, 'destroy']);
	Route::get('/newsletter/messages', [NewsletterController::class, 'messages']);
	Route::post('/newsletter/messages', [NewsletterController::class, 'storeMessage']);
	Route::post('/newsletter/messages/{id}/send', [NewsletterController::class, 'sendMessage']);
});
