<!DOCTYPE html>
<html>
    <head>
        <title>Funny Facts</title>
    </head>
    <body>
        <!-- Handle user options -->
        <?php
            if (isset($_POST['music']) or isset($_POST['countries']))
            {
                if (empty($_POST['nickname']))
                {
                    # Return appropriate error
                }
                else
                {
                    $nickname = stripslashes($_POST['nickname']);

                    # Check if nickname exists in file, else create new nickname
                    $lines = file('users.txt');
                    $found = false;
                    foreach($lines as $line)
                    {
                        # If found
                        if(strpos($line, $nickname) !== false)
                            $found = true;
                    }
                    
                    # Save new user to text file
                    if ($found === false)
                    {
                        $Users = fopen("users.txt", "a");
                        fwrite($Users, $nickname . "-0\n");
                        fclose($Users);
                    }

                    if (isset($_POST['music']))
                    {
                        # Link to start music quiz
			header("Location: musicQuiz.php");
                    }
                    else
                    {
                        # Link to start countries quiz
			header("Location: countriesQuiz.php");
                    }
                }

            }
            elseif (isset($_POST['leaderboard']))
            {
                # Link to leaderboard page
		header("Location: leaderboard.php");
            }
            elseif (isset($_POST['quit']))
            {
                # Link to quit page
		header("Location: exit.php");
            }
        ?>

        <!-- Start of page -->
        <h1>Welcome to Funny Facts!</h1>
        <h3>Enter your nickname below and pick a topic to get started!</h3>
        <table border = "0">
            <form action = "mainmenu.php" method = "POST">
                <tr>
                    <td align='right'>
                        <b>Nickname:</b>
                    </td>
                    <td align='left'>
                        <input type ='text' name='nickname'/>
                    </td>
                </tr>
                <tr>
                    <td>Select your topic below!</td>
                </tr>
                <tr>
                    <td align='center'>
                        <input type='submit' name='music' value='Music'/>
                    </td>
                    <td align='center'>
                        <input type='submit' name='countries' value='Countries'/>
                    </td>
                </tr>

                <tr><td></td></tr>

                <tr>
                    <td>Options:</td>
                </tr>
                <tr>
                    <td align='center'>
                        <input type='submit' name='leaderboard' value='Leaderboards'/>
                    </td>
                    <td align='center'>
                        <input type='submit' name='quit' value ='Quit'/>
                    </td>
                </tr>
            </form>
        </table>
    </body>
</html>