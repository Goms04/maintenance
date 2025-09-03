<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <table>
        <thead>
            <tr>
                <th>N°</th>
                <th>client</th>
                <th>Agence</th>
                <th>DESIGNATION DU MATERIEL</th>
                <th>OBSERVATIONS</th>
                <th>RECOMMANDATIONS</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rapports as $rapport)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td> {{ optional(optional($rapport->agency)->client)->name ?? 'Aucun client' }}</td>
                <td> {{ optional($rapport->agency)->name ?? 'Aucune agence' }}</td>
                <td>{{ $rapport->materiel }}</td>
                <td>{{ $rapport->observations }}</td>
                <td>{{ $rapport->recommandations }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>