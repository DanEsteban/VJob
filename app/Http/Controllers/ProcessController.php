<?php

namespace App\Http\Controllers;

use App\Models\InventoriesCustomers;
use App\Models\User;
use App\Models\Processes;
use App\Models\ProcessPhases;
use App\Models\ProcessStage;
use App\Models\ProcessCondition;
use Illuminate\Http\Request;

class ProcessController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:process.index')->only('index'); 
        $this->middleware('can:process.create')->only('create', 'store');
        $this->middleware('can:process.edit')->only('edit', 'update');
        $this->middleware('can:process.destroy')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $process = Processes::all();
        foreach ($process as $pr) {
           $user = User::where('id', $pr->responsible)->value('name');
           $pr->responsible = $user;
        }

        return view('process.index', compact('process'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::all();

        return view('process.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   

        $process = new Processes;
        $process->description = $request->process_name;
        if($request->check_responsible_process != "0"){
            $process->has_responsible = $request->check_responsible_process;
        }

        if($request->process_member != "0"){
            $process->id_responsible= $request->process_member;
        }
        $process->save();
        
        $count_phases = $request->process_phases_number;
        for ($i=0; $i < $count_phases; $i++) { 
            $phase = "phase_name".$i;
            if($request->input($phase)){
                $process_phase = new ProcessPhases;
                $phase_responsible = "check_responsible_phase".$i;
                $phase_member = "phase_member".$i;
                $process_phase->id_process = $process->id;
                $process_phase->description = $request->input($phase);
                if($request->input($phase_responsible) != "0"){
                    $process_phase->has_responsible = $request->input($phase_responsible);
                }

                if($request->input($phase_member) != "0"){
                    $process_phase->id_responsible = $request->input($phase_member);
                }

                $process_phase->save();

                $phase_number = "Phase".$i+1;
                $stage_name = "stage_name".$phase_number;
                $stage_responsible = "check_responsible_stage".$phase_number;
                $stage_member = "stage_member".$phase_number;
                $stage_inventory_receive = "process_inventory_received".$phase_number;
                $stage_condition = "check_condition".$phase_number;
                $stage_date = "check_date_stage".$phase_number;
                $stage_attachment = "check_attachment_stage".$phase_number;
                $stage_attachment_customer = "check_attachment_stage2".$phase_number;
                $stage_instruction = "check_instruction_stage".$phase_number;
                $stage_compare = "compare_switch_stage".$phase_number;
                $stage_mail = "mail_switch_stage".$phase_number;
                $stage_code_label = "code_switch_stage".$phase_number;

                $count_stage = count($request->input($stage_name));
                for ($j=0; $j < $count_stage; $j++) { 
                    $process_stage = new ProcessStage;
                    $process_stage->id_phase = $process_phase->id;
                    $process_stage->description = $request->input($stage_name)[$j];
                    if($request->input($stage_responsible)[$j]){
                        $process_stage->has_responsible = $request->input($stage_responsible)[$j];
                    }
                    if($request->input($stage_member)[$j] != "0"){
                        $process_stage->id_responsible = $request->input($stage_member)[$j];
                    }

                    if($request->input($stage_inventory_receive)[$j] == "on"){
                        $process_stage->has_inventory_received = 1;
                    }
                    else{
                        $process_stage->has_inventory_received = $request->input($stage_inventory_receive)[$j];
                    }

                    $process_stage->has_condition = $request->input($stage_condition)[$j];
                    $process_stage->has_date = $request->input($stage_date)[$j];
                    $process_stage->has_attachment = $request->input($stage_attachment)[$j];
                    $process_stage->has_attachment_customer = $request->input($stage_attachment_customer)[$j];
                    $process_stage->has_instructions = $request->input($stage_instruction)[$j];
                    $process_stage->has_comparison = $request->input($stage_compare)[$j];
                    $process_stage->has_send_mail = $request->input($stage_mail)[$j];
                    $process_stage->has_code_label = $request->input($stage_code_label)[$j];
                    $process_stage->save();

                    if($request->input($stage_condition)[$j] == "1"){
                        $condition_question = "condition_stage".$phase_number;
                        $condition_yes = "condition_stage_yes".$phase_number;
                        $action_yes = "action_stage_yes".$phase_number;
                        $condition_no = "condition_stage_no".$phase_number;
                        $action_no = "action_stage_no".$phase_number;

                        $process_condition = new ProcessCondition;
                        $process_condition->id_stage = $process_stage->id;
                        $process_condition->question = $request->input($condition_question)[$j];
                        $process_condition->message_yes = $request->input($condition_yes)[$j];
                        $process_condition->message_no = $request->input($condition_no)[$j];
                        $process_condition->action_yes = $request->input($action_yes)[$j];
                        $process_condition->action_no = $request->input($action_no)[$j];
                        $process_condition->save();
                    }

                }
            }
        }

        return redirect()->route('process.index')->with('info', 'A new record has been created')->send();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $users = User::all();
        $group_phases = array();
        $process = Processes::find($id);
        $process_phases = ProcessPhases::where('id_process', $process->id)->get();
        if($process_phases){
            foreach ($process_phases as $phase) {
                $process_stages = ProcessStage::where('id_phase', $phase->id)->get();
                $group_phases[] = [
                    'phase' => $phase,
                    'stages' => $process_stages
                ];
            }
        }
        
        return view('process.edit', compact('process', 'group_phases', 'users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $process = Processes::find($id);
        $process->description = $request->process_name;
        if($request->check_responsible_process != "0"){
            $process->has_responsible = $request->check_responsible_process;
        }
        if($request->process_member != "0"){
            $process->id_responsible= $request->process_member;
        }
        $process->save();

        $process_phase = ProcessPhases::where('id_process', $process->id)->get();
        if($process_phase){
            foreach ($process_phase as $phase) {
                $process_stages = ProcessStage::where('id_phase', $phase->id)->get();
                if($process_stages){
                    foreach ($process_stages as $stage) {
                        if($stage->has_condition == 1){
                            $condition = ProcessCondition::where('id_stage', $stage->id)->first();
                            $condition->delete();
                        }

                        $stage->delete();
                    }
                }
                $phase->delete();
            }
        }

        $count_phases = $request->process_phases_number;
        for ($i=0; $i < $count_phases; $i++) {
            $phase = "phase_name".$i;
            if($request->input($phase)){
                $process_phase = new ProcessPhases;     
                $phase_responsible = "check_responsible_phase".$i;
                $phase_member = "phase_member".$i;
                $process_phase->id_process = $process->id;
                $process_phase->description = $request->input($phase);
                if($request->input($phase_responsible) != "0"){
                    $process_phase->has_responsible = $request->input($phase_responsible);
                }

                if($request->input($phase_member) != "0"){
                    $process_phase->id_responsible = $request->input($phase_member);
                }
                $process_phase->save();

                $phase_number = "Phase".$i+1;
                $stage_name = "stage_name".$phase_number;
                $stage_responsible = "check_responsible_stage".$phase_number;
                $stage_member = "stage_member".$phase_number;
                $stage_inventory_receive = "process_inventory_received".$phase_number;
                $stage_condition = "check_condition".$phase_number;
                $stage_date = "check_date_stage".$phase_number;
                $stage_attachment = "check_attachment_stage".$phase_number;
                $stage_attachment_customer = "check_attachment_stage2".$phase_number;
                $stage_instruction = "check_instruction_stage".$phase_number;
                $stage_compare = "compare_switch_stage".$phase_number;
                $stage_mail = "mail_switch_stage".$phase_number;
                $stage_code_label = "code_switch_stage".$phase_number;

                $count_stage = count($request->input($stage_name));
                for ($j=0; $j < $count_stage; $j++) { 
                    $process_stage = new ProcessStage;
                    $process_stage->description = $request->input($stage_name)[$j];
                    $process_stage->id_phase = $process_phase->id;
                    if($request->input($stage_responsible)[$j]){
                        $process_stage->has_responsible = $request->input($stage_responsible)[$j];
                    }

                    if($request->input($stage_member) == "1"){
                        $process_stage->id_responsible = $request->input($stage_member)[$j];
                    }

                    if($request->input($stage_inventory_receive)[$j] == "on"){
                        $process_stage->has_inventory_received = 1;
                    }
                    else{
                        $process_stage->has_inventory_received = $request->input($stage_inventory_receive)[$j];
                    }

                    $process_stage->has_condition = $request->input($stage_condition)[$j];
                    $process_stage->has_date = $request->input($stage_date)[$j];
                    $process_stage->has_attachment = $request->input($stage_attachment)[$j];
                    $process_stage->has_attachment_customer = $request->input($stage_attachment_customer)[$j];
                    $process_stage->has_instructions = $request->input($stage_instruction)[$j];
                    $process_stage->has_comparison = $request->input($stage_compare)[$j];
                    $process_stage->has_send_mail = $request->input($stage_mail)[$j];
                    $process_stage->has_code_label = $request->input($stage_code_label)[$j];
                    $process_stage->save();

                    if($request->input($stage_condition)[$j] == "1"){
                        $condition_question = "condition_stage".$phase_number;
                        $condition_yes = "condition_stage_yes".$phase_number;
                        $action_yes = "action_stage_yes".$phase_number;
                        $condition_no = "condition_stage_no".$phase_number;
                        $action_no = "action_stage_no".$phase_number;

                        $process_condition = new ProcessCondition;
                        $process_condition->id_stage = $process_stage->id;
                        $process_condition->question = $request->input($condition_question)[$j];
                        $process_condition->message_yes = $request->input($condition_yes)[$j];
                        $process_condition->message_no = $request->input($condition_no)[$j];
                        $process_condition->action_yes = $request->input($action_yes)[$j];
                        $process_condition->action_no = $request->input($action_no)[$j];
                        $process_condition->save();
                    }

                }
            }              
        }

        return redirect()->route('process.index')->with('info', 'A new record has been edited')->send();   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $process = Processes::find($id);
        $process_phase = ProcessPhases::where('id_process', $process->id)->get();
        if($process_phase){
            foreach ($process_phase as $phase) {
                $process_stages = ProcessStage::where('id_phase', $phase->id)->get();
                if($process_stages){
                    foreach ($process_stages as $stage) {
                        if($stage->has_condition == 1){
                            $condition = ProcessCondition::where('id_stage', $stage->id)->first();
                            $condition->delete();
                        }

                        $stage->delete();
                    }
                }
                $phase->delete();
            }
        }

        $process->delete();

        return redirect()->route('process.index')->with('info', 'The process has been deleted')->send();   
    }
}
