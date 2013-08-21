War Zone: a war simulator in PHP
=============

This was inspired by [this blog post](http://www.reddit.com/r/PHP/comments/1kq398/autofight_a_php_job_interview_task_tutorial_part_1/). I read the premise:

    Make two armies fight each other in PHP. Include an element of randomness.
    
And I immediately thought, "Wow! That's a cool idea!"

I purposely didn't read the rest of the post until after I implemented my prototype. While I ultimately made basically the same restrictions upon myself, the overall result wound up being somewhat different.

### Restrictions

I imposed a few restrictions on my code:

* Other than the initial loader, it would be object-oriented (OOP)
* Output would have color
* It would be modular and easily extensible
* It would be entirely my own code.

As you can see, the restrictions were similar to the original poster's comments. Let's examine the results of those restrictions below.

### Object-Oriented

I wound up with a few base classes:

* War - The entire war and handles creating armies, engaging battle, and cleaning up afterward.
* Army - An army full of soldiers.
* Unit - A person in the army. This class was specifically designed to be extensible so that we can have different units available in the army.
    * Soldier - The most basic combat unit.
    * Medic - Cannot attack. Can heal allies.
* Battle - A confrontation between two armies.
* Report - Handles reporting events in full color.

### Color

All output comes from the Report class, which simply echoes data to the screen. It uses ASCII color to highlight different parts of the output, such as soldier names, damage taken, and deaths.

### Modularity and Extensibility

The ```Unit``` class is the first class to be designed with extensibility in mind. This class contains the base attributes and methods that all army units will have, such as level_up(), generate_name() and generate_stats().

The ```Soldier``` class is the most basic combat unit. It can attack enemy units, dealing damage.
The ```Medic``` class extends the Unit class. Medics can heal allies.

Further unit-types will be developed as time goes on.

### Entirely my own code

All code in this War Zone app is built into PHP. There are no special extensions or plugins and there are no dependencies of any kind.

## Starting a War

OK, so this is the interesting part. At its simplest, a war is just a bunch of armies fighting each other. The last army standing wins.

The process is basically as follows:

1. Generate armies
2. Generate soldiers for the armies
3. Pick two random armies
4. Begin a battle
    * Battle lasts 2-5 rounds (random)
    * Winner is whichever army produces the most kills 
5. If an army is out of units OR all units are non-combat units (i.e., medics), remove the army.
6. Repeat Step 4 until only one army remains.

## Anatomy of a Battle

Battles are pretty straightforward. We randomly picked two armies to participate in the battle. One army gets first strike (element of surprise or something).

1) Loop through each unit in the first army
2) Each unit takes action (attack enemy, heal ally)
3) If a unit's HP goes below zero, they die and are removed from the army.
4) Repeat steps 1-3 with the second army.
5) If an army runs out of soldiers OR we finish all the rounds, battle ends.

## Experience

Each time a unit takes action, they gain experience if it is completed successfully. If a soldier successfully KILLS an opponent, then they get experience. If a Medic heals an ally, then they get experience.

Once a unit achieves a certain amount of experience, they level up, increasing hit points and strength. We also reset their experience and increase the "next level" value to make getting to the next level even harder.

## Conclusion

This is a fun project to do. I had a lot of fun. If you want to try it out, just clone it and run ```php warzone.php``` and watch as the battle proceeds before your very eyes.

Enjoy. Any feedback is welcome!
