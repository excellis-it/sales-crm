
    <table id="myTable" class="dd table table-striped  table-hover" style="width:100%">
        <thead>
            <tr>
                <th> Name</th>
                <th> Email</th>
                <th> Phone</th>
                <th> Employee Id</th>
                <th> Date Of Joining</th>
                {{-- <th>No. of prospect</th> --}}
                <th> Status</th>
                <th> Action</th>
            </tr>
        </thead>
        <tbody >
            @if (count($business_development_excecutives) == 0)
                <tr>
                    <td colspan="7" class="text-center">No BDE Found</td>
                </tr>
            @else
            @foreach ($business_development_excecutives as $key => $business_development_excecutive)
                <tr>
                    <td class="edit-route" data-route="{{ route('bde.edit', $business_development_excecutive->id) }}">{{ $business_development_excecutive->name }}</td>
                    <td class="edit-route" data-route="{{ route('bde.edit', $business_development_excecutive->id) }}">{{ $business_development_excecutive->email }}</td>
                    <td class="edit-route" data-route="{{ route('bde.edit', $business_development_excecutive->id) }}">{{ $business_development_excecutive->phone }}</td>
                    <td class="edit-route" data-route="{{ route('bde.edit', $business_development_excecutive->id) }}">{{ $business_development_excecutive->employee_id }}</td>
                    <td class="edit-route" data-route="{{ route('bde.edit', $business_development_excecutive->id) }}">{{ $business_development_excecutive->date_of_joining }}</td>
                    {{-- <td><a href="{{ route('bdm.prospects.index',['user_id'=>$business_development_excecutive->id]) }}">{{ $business_development_excecutive->prospects->count() }}</a></td> --}}
                    <td>
                        <div class="button-switch">
                            <input type="checkbox" id="switch-orange" class="switch toggle-class"
                                data-id="{{ $business_development_excecutive['id'] }}"
                                {{ $business_development_excecutive['status'] ? 'checked' : '' }} />
                            <label for="switch-orange" class="lbl-off"></label>
                            <label for="switch-orange" class="lbl-on"></label>
                        </div>
                    </td>
                    <td>

                        <a title="Delete BDE"
                            data-route="{{ route('bde.delete', $business_development_excecutive->id) }}"
                            href="javascipt:void(0);" id="delete"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
            @endforeach
            @endif
        </tbody>
    </table>


<tr>
    <td colspan="12">
        <div class="d-flex justify-content-center">
            {!! $business_development_excecutives->links() !!}
        </div>
    </td>
</tr>
