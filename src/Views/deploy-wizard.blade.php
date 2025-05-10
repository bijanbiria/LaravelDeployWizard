<!DOCTYPE html>
<html>
    <head>
        <title>Laravel Deploy Wizard</title>
    </head>
    <body>
        <h1>Laravel Deploy Wizard</h1>

        @if (session('error'))
            <p style="color: red;">{{ session('error') }}</p>
        @endif

        <form method="POST" action="{{ url(config('installer.route', 'install')) }}">
            @csrf
            <label>Database Host:</label>
            <input type="text" name="db_host" required>
            <br>
            <label>Database Port:</label>
            <input type="text" name="db_port" required>
            <br>
            <label>Database Name:</label>
            <input type="text" name="db_database" required>
            <br>
            <label>Database Username:</label>
            <input type="text" name="db_username" required>
            <br>
            <label>Database Password:</label>
            <input type="password" name="db_password">
            <br>
            <button type="submit">Install</button>
        </form>
    </body>
</html>