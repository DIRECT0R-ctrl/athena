<?php

class NotificationService {
    public static function sendTaskCreated(Task $task, User $creator) {
        // In a real app, send email
        // For now, just log
        error_log("Task '{$task->getTitle()}' created by {$creator->getFullname()}");
    }
    
    public static function sendTaskUpdated(Task $task, User $updater) {
        error_log("Task '{$task->getTitle()}' updated by {$updater->getFullname()}");
    }
    
    public static function sendCommentAdded(Comment $comment, User $commenter) {
        error_log("Comment added by {$commenter->getFullname()} on task {$comment->getTaskId()}");
    }
}