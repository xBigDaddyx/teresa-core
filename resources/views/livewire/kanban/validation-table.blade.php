<div>
    <div class="card bg-white">
        <div class="card-body">
            <h2 class="card-title">Polybag Lists</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Polybag Code</th>
                        <th>Type</th>
                        <th>Scanned By</th>
                        <th>Scanned At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($carton->polybags as $polybag)
                    <tr wire:key="{{ $polybag->id }}">


                        <td>
                            <span class="font-bold">{{$polybag->polybag_code ?? '-'}}</span>
                            <br />

                        </td>
                        <td>
                            {{$polybag->cartonBox->type ?? '-'}}
                        </td>
                        <td>
                            {{\Domain\Users\Models\User::find($polybag->created_by)->name ?? '-'}}
                        </td>
                        <td>
                            <span class="badge badge-primary badge-sm">Scanned at : {{\Carbon\Carbon::parse($polybag->created_at)->format("d-m-Y H:i:s") ?? '-'}}</span>
                        </td>
                        <td>
                            <button class="btn btn-error" wire:click="delete('{{$polybag->id}}')" wire:confirm="Are you sure you want to delete this polybag?">
                                <x-tabler-trash class="w-5 h-5" />
                                Delete
                            </button>
                        </td>

                    </tr>
                    @endforeach


                </tbody>
            </table>
        </div>
    </div>

</div>
