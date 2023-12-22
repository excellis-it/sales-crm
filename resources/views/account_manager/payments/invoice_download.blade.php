<html>
<head>
    <title>Payments Details</title>
    
</head>
<style>
    table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
}
</style>
<body>
    <div class="">
        <img src="" >
       
        <h3>Transaction Details:</h3>
        @if($milestone_detail)
        <table style="width:100%">
            <thead>
                <tr>
                    <th>Project Name</th>
                    <th>Milestone Name</th>
                    <th>Milestone value</th>
                    <th>Payment Mode</th>
                    <th>Payment date</th>
                </tr>
            </thead>
            <tbody>
               
                <tr>
                    <td align="center">{{$milestone_detail->project->business_name}}</td>
                    <td align="center">{{$milestone_detail->milestone_name}}</td>
                    <td align="center">{{$milestone_detail->milestone_value}}</td>
                    <td align="center">{{date('d M, Y', strtotime($milestone_detail->payment_date))}}</td>
                    <td align="center">{{$milestone_detail->payment_mode}}</td>                                                                       
                </tr>
               
                <tr>
                    <td colspan="5," align="right"></td>
                </tr>
            </tbody>
        </table>
        @else
        <h5>No Payments Found...</h5>
        @endif
    
    </div>
    
</body>
</html>