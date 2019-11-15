<table>
    <thead>
    <tr>
        <th>Contact id</th>
        <th>Contact</th>
        <th>Updated by</th>
        <th>Changes</th>
        <th>Acts</th>
        <th>Response</th>
        <th>Updated at</th>
    </tr>
    </thead>
    <tbody>
    @foreach($logs as $log)
        <tr>
            <td>{{ $log->contact_id }}</td>
            <td>{{ $log->contact }}</td>
            <td>{{ $log->updated_by }}</td>
            <td>{{ $log->changes }}</td>
            <td>{{ $log->act }}</td>
            <td>{{ $log->response }}</td>
            <td>{{ $log->updated_at }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
