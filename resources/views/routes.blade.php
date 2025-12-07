<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Routes Viewer</title>

    <style>
        body {
            font-family: 'fira code', sans-serif;
            background: #f5f7fa;
            padding: 20px;
        }

        input[type="text"] {
            width: 300px;
            padding: 8px 12px;
            margin-bottom: 20px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
        }

        th {
            background: #2d3748;
            color: #fff;
            padding: 12px;
            text-align: left;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        td {
            padding: 10px;
            border-bottom: 1px solid #e5e7eb;
        }

        th,
        td {
            font-size: 13px;
        }

        tr:hover {
            background: #f0f4f8;
        }

        .method {
            padding: 4px 10px;
            border-radius: 6px;
            color: white;
            font-size: 13px;
            font-weight: bold;
            display: inline-block;
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

        .controller-name {
            color: #2ecc71;
            font-weight: bold;
        }

        .method-name {
            color: #3498db;
            font-weight: normal;
        }
    </style>
</head>

<body>
    <h2 style="margin-bottom: 15px;">Laravel Routes Viewer</h2>

    <!-- Search Input -->
    <input type="text" id="searchInput" placeholder="Search routes...">

    <table id="routesTable">
        <thead>
            <tr>
                <th>Method</th>
                <th>URI</th>
                <th>Name</th>
                <th>Action</th>
                <th>Middleware</th>
                <th>Domain</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($routes as $route)
                <tr>
                    <td>
                        @foreach ($route->methods() as $method)
                            @if ($method !== 'HEAD')
                                <span class="method {{ $method }}">{{ $method }}</span>
                            @endif
                        @endforeach
                    </td>

                    <td>{{ $route->uri() }}</td>
                    <td>{{ $route->getName() }}</td>
                    <td class="action-cell">{{ $route->getActionName() }}</td>
                    <td>{{ implode(', ', $route->middleware()) }}</td>
                    <td>{{ $route->domain() ?? '—' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        const searchInput = document.getElementById('searchInput');
        const table = document.getElementById('routesTable');
        const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

        searchInput.addEventListener('input', function() {
            const filter = searchInput.value.toLowerCase();

            for (let i = 0; i < rows.length; i++) {
                let rowText = rows[i].innerText.toLowerCase();
                rows[i].style.display = rowText.includes(filter) ? '' : 'none';
            }
        });

        const actionCells = document.querySelectorAll('.action-cell');

        actionCells.forEach(cell => {
            let fullText = cell.innerText.trim();
            let parts = fullText.split('\\');
            let lastPart = parts[parts.length - 1];
            if (lastPart.includes('@')) {
                let [controller, method] = lastPart.split('@');
                cell.innerHTML = parts.slice(0, -1).join('\\') + '\\' +
                    '<span class="controller-name">' + controller + '</span>' +
                    '@' +
                    '<span class="method-name">' + method + '</span>';
            }
        });
    </script>
</body>

</html>
