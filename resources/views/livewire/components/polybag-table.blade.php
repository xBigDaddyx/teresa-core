<div class="card overflow-x-auto bg-white rounded-xl shadow-md" wire:poll.keep-alive>
    <div class="card-body">
        <div>
            <div class="card-title">
                <h2>Validated Polybags</h2>
            </div>
            <p class="card-subtitle">Lists of scanned and validated polybags</p>
        </div>

        <table class="table table-xs">
            <!-- head -->
            <thead>
                <tr>
                    <th>Carton Box</th>
                    <th>Polybag</th>
                    <th>Style</th>
                    <th>Validated By</th>

                </tr>
            </thead>
            <tbody>
                @if(session()->get('carton.type') === 'SOLID')
                @foreach ($carton->polybags as $polybag)
                <tr>
                    <td>
                        <div class="flex items-center space-x-3">
                            <div>
                                <div class="font-bold">{{$polybag->cartonBox->box_code}}</div>
                                <div class="text-sm opacity-50">{{$polybag->carton_box_id}}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="flex items-center space-x-3">
                            <div>
                                <div class="font-bold">{{$polybag->polybag_code}}</div>
                                <div class="text-sm opacity-50">{{$carton->type}} Type</div>
                            </div>
                        </div>
                    </td>
                    <td>{{$carton->packingList->style_no}}</td>
                    <td>
                        <div class="flex items-center space-x-3">
                            <div>
                                <div class="font-bold">{{$polybag->createdBy}}</div>
                                <div class="text-sm opacity-50">{{$polybag->created_at}}</div>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
                @else
                @foreach ($carton->polybags as $polybag)
                <tr>
                    <td>
                        <div class="flex items-center space-x-3">
                            <div>
                                <div class="font-bold">{{$polybag->cartonBox->box_code}}</div>
                                <div class="text-sm opacity-50">{{$polybag->carton_box_id}}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="flex items-center space-x-3">
                            <div>
                                <div class="font-bold">{{$polybag->polybag_code}}</div>
                                <div class="text-sm opacity-50">{{$carton->type}} Type</div>
                            </div>
                        </div>
                    </td>
                    <td>{{$carton->packingList->style_no}}</td>
                    <td>
                        <div class="flex items-center space-x-3">
                            <div>
                                <div class="font-bold">{{$polybag->createdBy}}</div>
                                <div class="text-sm opacity-50">{{$polybag->created_at}}</div>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
                @endif
            </tbody>
            <!-- foot -->
            <tfoot>

            </tfoot>

        </table>


    </div>

</div>
