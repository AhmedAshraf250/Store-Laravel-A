<!DOCTYPE html>
<html>

<head>
    <title>Route Viewer</title>
    <style>
        body {
            font-family: Arial;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            font-size: 14px;
        }

        /* رأس جدول ثابت */
        th {
            background: #333;
            color: white;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        tr:nth-child(even) {
            background: #fafafa;
        }

        /* ألوان Methods */
        .method-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: bold;
            color: white;
        }

        tr:hover {
            background: #ced5db;
        }


        .GET {
            background: #28a745;
        }


        .POST {
            background: #007bff;
        }


        .PUT {
            background: #6f42c1;
        }


        .PATCH {
            background: #17a2b8;
        }


        .DELETE {
            background: #dc3545;
        }


        .HEAD {
            background: #fd7e14;
        }


        .OPTIONS {
            background: #20c997;
        }
    </style>
</head>

<body>

    <h2>Laravel Route Viewer</h2>

    <table>
        <thead>
            <tr>
                <th>Method</th>
                <th>URI</th>
                <th>Name</th>
                <th>Action</th>
                <th>Middleware</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($routes as $route)
                <tr>
                    <td>
                        @foreach (explode('|', $route['method']) as $method)
                            <span class="method-badge {{ $method }}">{{ $method }}</span>
                        @endforeach
                    </td>

                    <td>{{ $route['uri'] }}</td>
                    <td>{{ $route['name'] }}</td>
                    <td>{{ $route['action'] }}</td>
                    <td>{{ $route['middleware'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
