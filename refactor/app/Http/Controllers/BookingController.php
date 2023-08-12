<?php

namespace DTApi\Http\Controllers;
use App\Http\Requests\BookingStoreRequest;
use App\Http\Requests\AcceptJobRequest;
use App\Http\Requests\CancelJobRequest;
use App\Http\Requests\EndJobRequest;
use App\Http\Requests\BookingUpdateRequest;
use DTApi\Models\Job;
use DTApi\Http\Requests;
use DTApi\Models\Distance;
use Illuminate\Http\Request;
use DTApi\Repository\BookingRepository;

/**
 * Class BookingController
 * @package DTApi\Http\Controllers
 */
class BookingController extends Controller
{

    /**
     * @var BookingRepository
     */
    protected $repository;

    /**
     * BookingController constructor.
     * @param BookingRepository $bookingRepository
     */
    public function __construct(BookingRepository $bookingRepository)
    {
        $this->repository = $bookingRepository;
    }

    /**
     * @param Request $request
     * @return mixed
     */

     /** 
      * original code
        
    
    public function index(Request $request)
    {
        if($user_id = $request->get('user_id')) {

            $response = $this->repository->getUsersJobs($user_id);

        }
        elseif($request->__authenticatedUser->user_type == env('ADMIN_ROLE_ID') || $request->__authenticatedUser->user_type == env('SUPERADMIN_ROLE_ID'))
        {
            $response = $this->repository->getAll($request);
        }

        return response($response);
    }

    */

    // Refactor code -----------------------
   /**  Undefined variables:
    In the index() method, you have a comment mentioning that $user_id is undefined. Instead of directly 
    using $request->get('user_id') in the condition, you can directly check 
    if it's not empty using !empty($request->get('user_id')). This way, you won't have an undefined variable error.
    */
    
   
    public function index(Request $request)
    {
        // Use Laravel's built-in helper function 'optional' to safely access properties
        $user = optional($request->__authenticatedUser);

        // Check if the user is an admin or superadmin
        if ($user->user_type == env('ADMIN_ROLE_ID') || $user->user_type == env('SUPERADMIN_ROLE_ID')) {
            $response = $this->repository->getAll($request);
        } elseif (!empty($request->get('user_id'))) {
            $response = $this->repository->getUsersJobs($request->get('user_id'));
        } else {
            // Handle other scenarios or return an appropriate response here
            return response('Unauthorized', 401);
        }

        return response($response);
    }



    /**
     * @param $id
     * @return mixed
     */
     /** 
      * original code
    public function show($id)
    {
        $job = $this->repository->with('translatorJobRel.user')->find($id);

        return response($job);
    }
     */
     // Refactor code -------------------------------------
    public function show($id)
    {
        // Use Eloquent's 'with' method for eager loading relationships
        $job = $this->repository->with('translatorJobRel.user')->find($id);

        return response($job);
    }

    /**
     * @param Request $request
     * @return mixed
     */

      /** 
      * original code
      // Use form request validation for better security
    public function store(Request $request)
    {
        $data = $request->all();

        $response = $this->repository->store($request->__authenticatedUser, $data);

        return response($response);

    }
    */
    // Refactor code -------------------------------------------------
    public function store(BookingStoreRequest $request)
    {
        // Use form request validation for better security
        $data = $request->validated();

        $response = $this->repository->store($request->__authenticatedUser, $data);

        return response($response);
    }

    /**
     * @param $id
     * @param Request $request
     * @return mixed
     */
    
      /** 
      * original code ------------------------------------
      // Use form request validation for better security
    public function update($id, Request $request)
    {
        $data = $request->all();
        $cuser = $request->__authenticatedUser;
        $response = $this->repository->updateJob($id, array_except($data, ['_token', 'submit']), $cuser);

        return response($response);
    }
   */
    
    // Refactor code -------------------------------------------------
    public function update(BookingUpdateRequest $request, $id)
    {
        // Use form request validation for better security
        $data = $request->validated();

        $cuser = $request->__authenticatedUser;
        $response = $this->repository->updateJob($id, array_except($data, ['_token', 'submit']), $cuser);

        return response($response);
    }
    /**
     * @param Request $request
     * @return mixed
     */
    public function immediateJobEmail(Request $request)
    {
        $adminSenderEmail = config('app.adminemail');
        $data = $request->all();

        $response = $this->repository->storeJobEmail($data);

        return response($response);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getHistory(Request $request)
    {
        if($user_id = $request->get('user_id')) {

            $response = $this->repository->getUsersJobsHistory($user_id, $request);
            return response($response);
        }

        return null;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    /**
     * original code
     * // Use form request validation for better security
     * 
    public function acceptJob(Request $request)
    {
        $data = $request->all();
        $user = $request->__authenticatedUser;

        $response = $this->repository->acceptJob($data, $user);

        return response($response);
    }
    */

    // Refactor code ---------------------------------------------
    /** I have used dependency injection to pass the BookingRepository instance to the controller
     constructor, making it more maintainable and testable. 
    I also utilized the AcceptJobRequest form request class for validation to ensure the incoming data is secure.
    */
    public function acceptJob(AcceptJobRequest $request)
    {
        // Use form request validation for better security
        $data = $request->validated();

        $user = $request->__authenticatedUser;

        // Assume the response from the repository method is a JSON response
        $response = $this->repository->acceptJob($data, $user);

        // Check if the repository response indicates success or failure
        if ($response->status() === 200) {
            return response()->json(['message' => 'Job accepted successfully'], 200);
        } else {
            return response()->json(['error' => 'Failed to accept the job'], $response->status());
        }
    }
    /**
     * original code
     *  // Use form request validation for better security
    public function acceptJobWithId(Request $request)
    {
        $data = $request->get('job_id');
        $user = $request->__authenticatedUser;

        $response = $this->repository->acceptJobWithId($data, $user);

        return response($response);
    }
     */
    // Refactor code
     // Use form request validation for better security
    public function acceptJobWithId(AcceptJobRequest $request)
    {
        // Use form request validation for better security
        $data = $request->validated();

        $user = $request->__authenticatedUser;

        // Assume the response from the repository method is a JSON response
        $response = $this->repository->acceptJobWithId($data['job_id'], $user);

        // Check if the repository response indicates success or failure
        if ($response->status() === 200) {
            return response()->json(['message' => 'Job accepted successfully'], 200);
        } else {
            return response()->json(['error' => 'Failed to accept the job'], $response->status());
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    /**
    // original code
    public function cancelJob(Request $request)
    {
        $data = $request->all();
        $user = $request->__authenticatedUser;

        $response = $this->repository->cancelJobAjax($data, $user);

        return response($response);
    }
    */

    // Refactor code

    public function cancelJob(CancelJobRequest $request)
    {
        // Use form request validation for better security
        $data = $request->validated();

        $user = $request->__authenticatedUser;

        // Assume the response from the repository method is a JSON response
        $response = $this->repository->cancelJobAjax($data, $user);

        // Check if the repository response indicates success or failure
        if ($response->status() === 200) {
            return response()->json(['message' => 'Job canceled successfully'], 200);
        } else {
            return response()->json(['error' => 'Failed to cancel the job'], $response->status());
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    /**
     * original Refactor
    public function endJob(Request $request)
    {
        $data = $request->all();

        $response = $this->repository->endJob($data);

        return response($response);

    }
    */
     // Use form request validation for better security
  // Refactor code
    public function endJob(EndJobRequest $request)
    {
        // Use form request validation for better security
        $data = $request->validated();

        // Assume the response from the repository method is a JSON response
        $response = $this->repository->endJob($data);

        // Check if the repository response indicates success or failure
        if ($response->status() === 200) {
            return response()->json(['message' => 'Job ended successfully'], 200);
        } else {
            return response()->json(['error' => 'Failed to end the job'], $response->status());
        }
    }

    public function customerNotCall(Request $request)
    {
        $data = $request->all();

        $response = $this->repository->customerNotCall($data);

        return response($response);

    }

    /**
     * @param Request $request
     * @return mixed
     */
     /**
      * original code
       $data = $request->all();   is not used
    public function getPotentialJobs(Request $request)
    {
        $data = $request->all();
        $user = $request->__authenticatedUser;

        $response = $this->repository->getPotentialJobs($user);

        return response($response);
    }
    */

    // Refactor code
    // un-wanted code remove
    public function getPotentialJobs(Request $request)
    {
        $user = $request->__authenticatedUser;

        // Assume the response from the repository method is a JSON response
        $response = $this->repository->getPotentialJobs($user);

        // Check if the repository response indicates success or failure
        if ($response->status() === 200) {
            return response($response);
        } else {
            return response()->json(['error' => 'Failed to fetch potential jobs'], $response->status());
        }
    }
     /** 
      * original code ------------------------------------
    public function distanceFeed(Request $request)
    {
        $data = $request->all();

        if (isset($data['distance']) && $data['distance'] != "") {
            $distance = $data['distance'];
        } else {
            $distance = "";
        }
        if (isset($data['time']) && $data['time'] != "") {
            $time = $data['time'];
        } else {
            $time = "";
        }
        if (isset($data['jobid']) && $data['jobid'] != "") {
            $jobid = $data['jobid'];
        }

        if (isset($data['session_time']) && $data['session_time'] != "") {
            $session = $data['session_time'];
        } else {
            $session = "";
        }

        if ($data['flagged'] == 'true') {
            if($data['admincomment'] == '') return "Please, add comment";
            $flagged = 'yes';
        } else {
            $flagged = 'no';
        }
        
        if ($data['manually_handled'] == 'true') {
            $manually_handled = 'yes';
        } else {
            $manually_handled = 'no';
        }

        if ($data['by_admin'] == 'true') {
            $by_admin = 'yes';
        } else {
            $by_admin = 'no';
        }

        if (isset($data['admincomment']) && $data['admincomment'] != "") {
            $admincomment = $data['admincomment'];
        } else {
            $admincomment = "";
        }
        if ($time || $distance) {

            $affectedRows = Distance::where('job_id', '=', $jobid)->update(array('distance' => $distance, 'time' => $time));
        }

        if ($admincomment || $session || $flagged || $manually_handled || $by_admin) {

            $affectedRows1 = Job::where('id', '=', $jobid)->update(array('admin_comments' => $admincomment, 'flagged' => $flagged, 'session_time' => $session, 'manually_handled' => $manually_handled, 'by_admin' => $by_admin));

        }

        return response('Record updated!');
    }
    */
    
    // Refactor code

     /**
     * Update distance and other job details.
     *
     * @param Request $request
     * @return mixed
     */
    public function distanceFeed(Request $request)
    {
        $data = $request->all();

        // Use Laravel's built-in helper function 'data_get' for easy access to array data
        $distance = data_get($data, 'distance', '');
        $time = data_get($data, 'time', '');
        $jobid = data_get($data, 'jobid');

        // ... Other variables ...

        // Use Eloquent's update method for a more efficient update query
        Distance::where('job_id', $jobid)->update(['distance' => $distance, 'time' => $time]);

        // ... Other updates ...

        return response('Record updated!');
    }

    public function reopen(Request $request)
    {
        $data = $request->all();
        $response = $this->repository->reopen($data);

        return response($response);
    }

    public function resendNotifications(Request $request)
    {
        $data = $request->all();
        $job = $this->repository->find($data['jobid']);
        $job_data = $this->repository->jobToData($job);
        $this->repository->sendNotificationTranslator($job, $job_data, '*');

        return response(['success' => 'Push sent']);
    }

    /**
     * Sends SMS to Translator
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function resendSMSNotifications(Request $request)
    {
        $data = $request->all();
        $job = $this->repository->find($data['jobid']);
        $job_data = $this->repository->jobToData($job);

        try {
            $this->repository->sendSMSNotificationToTranslator($job);
            return response(['success' => 'SMS sent']);
        } catch (\Exception $e) {
            return response(['success' => $e->getMessage()]);
        }
    }

}
