<?php

namespace App\Http\Controllers;

use \Illuminate\Http\Request;

use App\NiceAction;
use App\NiceActionLog;
use DB;

class NiceActionController extends Controller {

	public function getHome() {
		$actions = NiceAction::all(); //Gets all the rows from the nice_actions database.
		//$actions = DB::table('nice_actions')->get(); //This is the longhand version of the line above.
		$actions = NiceAction::orderBy('niceness', 'desc')->get();

		$logged_actions = NiceActionLog::paginate(5);
		/*$logged_actions = NiceActionLog::whereHas('nice_action', function($query) {
			$query->where('name', '=', 'Kiss');
		})->get();*/ // Get the rows only where 'name' is equal to 'Kiss'.

		// Adds the nice_action_id that is equal to Hug into the nice_action_logs table
		/*$query = DB::table('nice_action_logs')
						->insert([
							'nice_action_id' => DB::table('nice_actions')->select('id')->where('name', 'Hug')->first()->id,
							'created_at' => date("Y-m-d H:i:s"),
							'updated_at' => date("Y-m-d H:i:s")
						]);*/

						

/*      $hugRename = NiceAction::where('name','Hug')->first();
		if($hugRename) {
			$hugRename->name = 'Bang';
			$hugRename->update();
		}
		

		$deleteWave = NiceAction::where('name', 'Wave')->first();
		if($deleteWave) {
			$deleteWave->delete();
		}
*/

		return view('home', ['actions' => $actions, 'logged_actions' => $logged_actions]);
	}

	public function getNiceAction($action, $name = null) {
		if($name === null) {
			$name = 'you';
		}

		$nice_action = NiceAction::where('name', $action)->first();
		$nice_action_log = new NiceActionLog();
		$nice_action->logged_actions()->save($nice_action_log);

		return view('actions.nice', ['action' => $action ,'name' => $name]);
	}

	public function postInsertNiceAction(Request $request) {
		$this->validate($request, [
			'name' => 'required|alpha|unique:nice_actions', //alpha - The field under validation must be entirely alphabetic characters. | unique:databaseTable - The field under validation must be unique on a given database table.
			'niceness' => 'required|numeric' //numeric - The field under validation must be numeric.
		]);

		$action = new NiceAction();
		$action->name = ucfirst(strtolower($request['name']));
		$action->niceness = $request['niceness'];
		$action->save();

		$actions = NiceAction::all();

		if($request->ajax()) {
			return response()->json();
		}

	    return redirect()->route('home');
	}

	private function transformName($name) {
		$prefix = 'QUEEN ';
		return $prefix . strtoupper($name);
	}
}