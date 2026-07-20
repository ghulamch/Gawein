<?php
if(\App\Models\Activity::count() == 0) { 
    for($i=1; $i<=10; $i++) {
        \App\Models\Activity::create(["user_name" => "User $i", "action" => "Melakukan login", "status" => "Selesai"]); 
    }
} 
if(\App\Models\Transaction::count() == 0) { 
    for($i=1; $i<=5; $i++) {
        \App\Models\Transaction::create(["amount" => 1500000, "status" => "success"]); 
    }
} 
echo "done";
