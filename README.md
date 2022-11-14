# CONFABULATE Recruitment Test

Thank you for taking the time to do our technical test. It consists of two parts:

- [A coding test](#coding-test)
- [A few technical questions](#technical-questions)

To submit your solution and answers, please upload a repository to GitHub, GitLab or whatever code hosting platform you prefer - **Please *do not* send us back a zip file !**

**Please ensure that it also contains a single markdown file with answers to the technical questions.**

## Coding Test

Our product provides tools for managers to plan staff schedules (rotas), __one week at a time (Monday to Sunday)__. 

Let's call our shop __FunHouse__. 

Staff who are employed to work at __FunHouse__ are __Black Widow__, __Thor__, __Wolverine__, __Gamora__.

### Overall project scope: single manning calculation for FunHouse

>>>
As a shop manager
I want to know how many **single manning minutes** there were in my shop **each day of this week**
So that I can calculate how much bonus I'll pay out daily. 
>>>

#### Why this scope is important?

Staff get paid an enhanced _bonus_ supplement when they are working alone in the shop. Shop managers can use the information gathered above to strategically plan new rotas in the future with less single manning hours, reducing the cost of running that shop.


#### Scenario One

>>>
```
Black Widow: |----------------------| Example 09:00 -> 17:00
Example Single Manning Minutes for this day = 480
```

__Given__ Black Widow working at FunHouse on Monday in one long shift

__When__ no-one else works during the day

__Then__ Black Widow receives single manning supplement for the whole duration of her shift. 
>>>




#### Scenario Two

>>>
```
Black Widow: |----------| Example 09:00 -> 12:00
Thor:                   |-------------| Example 12:00 -> 18:00
Example Single Manning Minutes for this day = 540
```

__Given__ Black Widow and Thor working at FunHouse on Tuesday

__When__ they only meet at the door to say hi and bye

__Then__ Black Widow receives single manning supplement for the whole duration of her shift

__And__ Thor also receives single manning supplement for the whole duration of his shift.
>>>

#### Scenario Three

>>>
```
Wolverine: |------------| Example 09:00 ->15:00
Gamora:       |-----------------| Example 12:30 -> 19:00
Example Single Manning Minutes for this day = 450 minutes (09:00->12:30 + 15:00->19:00)
```

__Given__ Wolverine and Gamora working at FunHouse on Wednesday

__When__ Wolverine works in the morning shift

__And__ Gamora works the whole day, starting slightly later than Wolverine

__Then__ Wolverine receives single manning supplement until Gamorra starts her shift

__And__ Gamorra receives single manning supplement starting when Wolverine has finished his shift, until the end of the day.
>>>

### Task requirements

Your task is to **implement a class** that receives a `Rota` and returns `SingleManning`, a DTO (Data Transfer Object) containing the __number of minutes worked alone in the shop each day of the week__.

You'll find a `migration.php` file attached, which is a standard Laravel migration file describing the data structure - **you do not need to implement this migration or models as part of the code test. They are just for reference**!

__Please ensure your code is easily readable.__

There is no time limit to complete the task (We suggest 2 hours maximum), and try to make sure that the following criteria is met:

1. Make sure that all the above scenarios would work.
2. Include unit tests.
3. We would like for you to **describe in *Given When Then* and provide a test ** for another scenario. (You do not need to implement a solution for it, just tell us if your current class would handle the solution or not)
4. Please only include the files absolutely necessary to complete the task. **We do not wish to see a full application - please spend your time on the logic of the class and your code style.**

## Technical Questions

Please answer the following questions in a markdown file called `Answers to technical questions.md`.

1. How long did you spend on the coding test? What would you add to your solution if you had more time?
2. Why did you choose PHP as your main programming language?
3. What is your favourite thing about your most familar PHP framework (Laravel / Symfony etc)? 
4. What is your least favourite thing about the above framework?

## migration.php File for reference
```
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCodeTestTables extends Migration
{
    public function up(): void
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('staff', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name');
            $table->string('surname');
            $table->unsignedInteger('shop_id');
            $table->timestamps();

            $table->foreign('shop_id')->references('id')->on('shops');
        });

        Schema::create('rotas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('shop_id');
            $table->date('week_commence_date');
            $table->timestamps();

            $table->foreign('shop_id')->references('id')->on('shops');
        });

        Schema::create('shifts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('rota_id');
            $table->unsignedInteger('staff_id');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->timestamps();

            $table->foreign('rota_id')->references('id')->on('rotas')->onDelete('cascade');
            $table->foreign('staff_id')->references('id')->on('staff');
        });


    }

    public function down(): void
    {
        Schema::dropIfExists('shifts');
        Schema::dropIfExists('rotas');
        Schema::dropIfExists('shops');
        Schema::dropIfExists('staff');
    }
}

```