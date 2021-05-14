<?php

/**
 * @SWG\Swagger(
 *   basePath="/api",
 *   @SWG\Info(
 *     title="Core API",
 *     version="1.0.0"
 *   )
 * )
 */


namespace App\Http\Controllers;


use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use mysql_xdevapi\Exception;
use Validator;

class SubjectController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/v1/document/subject/list",
     *     tags={"Subject"},
     *      summary="Get list of Subject information",
     *      description="Returns Subject data",
     *     @OA\Response(response="200", description="Successfully."),
     *     @OA\Response(response="404", description="Record Not found."),
     *     @OA\Response(response="400", description="Bad Request."),
     *     @OA\Response(response="500", description="Server Error.")
     * )
     */


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * get list of subject
     */

    public function list(Request $request)
    {
        try {
            $subject = Subject::all();

            return response()->json([
                "message" => "Success",
                "data" => $subject,
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            $response['code'] = 500;
            $response['message'] = "Error Occurred";
            $response['debug_message'] = $e->getMessage();
            return $response;
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/document/subject/create",
     *     tags={"Subject"},
     *     summary="POST Create of Subject information",
     *     description="Create of Subject",
     *
     *    @OA\RequestBody(
     *    required=true,
     *    description="subject_code and sub_name",
     *    @OA\JsonContent(
     *       required={"subject_code","sub_name"},
     *       @OA\Property(property="subject_code", type="string"),
     *       @OA\Property(property="sub_name", type="string")
     *    ),
     * ),
     *     @OA\Response(
     *     response="200",
     *     description="Successfully."),
     *
     *     @OA\Response(response="404", description="Record Not found."),
     *     @OA\Response(response="400", description="Bad Request."),
     *     @OA\Response(response="500", description="Server Error.")
     * )
     */

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * create a new subject
     */

    public function create(Request $request){

        $valid = Validator::make($request->all(),[
            'subject_code' => 'required',
            'sub_name' => 'required'

        ]);

        if ($valid->fails()) {

            return response()->json([
                "status_code" => 401,
                "message" => "Error Occurred",
                "error" =>$valid->errors()
            ], 401);
        }

        try{
            $subject_code = $request->subject_code;
            $sub_name = $request->sub_name;

            DB::transaction(function () use ( $subject_code, $sub_name)
            {
                $subject = Subject::create([
                    "subject_code" => $subject_code,
                    "sub_name" => $sub_name
                ]);

            });

            return response()->json([
                "status_code"=> 200,
                "message"=>"Success",
            ], Response::HTTP_OK);

        }catch (Exception $e){
            $response['message'] = $e->getMessage();
            return $response;
        }

    }

}
