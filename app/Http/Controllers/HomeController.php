<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ShopRequest;
use App\Http\Requests\RobotRequest;
use App\Model\Shop;
use App\Model\Robot;
use Session;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	$shops = Shop::get();

        return view('home')->with(['shops' => $shops]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    	abort(404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ShopRequest $request)
    {
        $create = Shop::create([
        	'width'=> $request->get('width'), 
        	'height'=> $request->get('height') 
        ]);

        Session::flash('message', 'New shop is created');

        return back();        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $shop = Shop::with('robots')->findorfail($id);

        $list = Shop::get();

        return view('shop')->with(['shop' => $shop, 'list' => $list]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ShopRequest $request, $id)
    {
        $shop = Shop::findorfail($id);
        $shop->update($request->all());

        Session::flash('message', 'Shop is saved');

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $shop = Shop::findorfail($id);
        $shop->robots()->delete();
        $shop->delete();

        Session::flash('message', 'Shop is deleted');

        return back();
    }


    public function robot_store(RobotRequest $request, $id)
    {
    	$position = explode(' ', $request->get('position'));
     
        $robot = Robot::create([
            'shop_id' => $id,
        	'x_pos' => $request->get('x'),
        	'y_pos' => $request->get('y'),
        	'heading' => $request->get('heading'),
        	'commands' => $request->get('commands'),        	
        ]);
        
        Session::flash('message', 'New robot is created');

        return back();
    }

    public function robot_destroy($id, $rid)
    {
        $robot = Robot::findorfail($rid);
        $robot->delete();

        Session::flash('message', 'Robot is deleted');

        return back();
    }

    private function robot_update($status, $result)
    {
        $flat_status = collect($status)->flatten();

        if ($flat_status->contains('Collision') || $flat_status->contains('Out of bounce'))
        {
            Session::flash('robots-updated', false);
        }else{
            $result->each(function($robot){                
                $value = explode(' ',end($robot['movements']));
                $robot = Robot::findorfail($robot['id']);
                $robot->update([
                    'x_pos' => $value[0],
                    'y_pos' => $value[1],
                    'heading' => $value[2]
                ]);
            });
            Session::flash('robots-updated', true);
        }
    }

    public function execute($id)
    {
    	$shop = Shop::with('robots')->findorfail($id);
        $result = $shop->getSimulationResult();
        $status = $shop->getSimulationStatus($result);

        $this->robot_update($status, $result);

        Session::flash('simulation-result', $result);
        Session::flash('simulation-status', $status);

        return redirect(sprintf('shop/%d#result', $id));
    }
}

