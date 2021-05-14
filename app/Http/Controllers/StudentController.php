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


use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use mysql_xdevapi\Exception;
use Validator;

class StudentController extends Controller
{


    /**
     * @OA\Get(
     *     path="/api/v1/document/student/list",
     *     tags={"Student"},
     *      summary="Get list of Student information",
     *      description="Returns Student data",
     *     @OA\Response(response="200", description="Successfully."),
     *     @OA\Response(response="404", description="Record Not found."),
     *     @OA\Response(response="400", description="Bad Request."),
     *     @OA\Response(response="500", description="Server Error.")
     * )
     */


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * get list of student
     */

    public function list(Request $request)
    {
        try {
            $student = Student::all();

            return response()->json([
                "message" => "Success",
                "data" => $student,
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            $response['code'] = 500;
            $response['message'] = "Error Occurred";
            $response['debug_message'] = $e->getMessage();
            return $response;
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/document/student/show/4",
     *     tags={"Student"},
     *      summary="Get list of Student by id",
     *      description="Returns Student data",
     *
     *     @OA\Response(response="200", description="Successfully."),
     *     @OA\Response(response="404", description="Record Not found."),
     *     @OA\Response(response="400", description="Bad Request."),
     *     @OA\Response(response="500", description="Server Error.")
     * )
     */


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * get list of student by id
     */

    public function show($id) {
        if (Student::where('id', $id)->exists()) {
            $student = Student::where('id', $id)->get();
            return response()->json([
                "message" => "Success",
                "data" => $student
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                "status_code" => 404,
                "message" => "Record not found",
            ], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/document/student/create",
     *     tags={"Student"},
     *     summary="POST Create of Student information",
     *     description="Create of Student",
     *
     *    @OA\RequestBody(
     *    required=true,
     *    description="name,contact_no and address",
     *    @OA\JsonContent(
     *       required={"name","contact_no","address"},
     *       @OA\Property(property="name", type="string"),
     *       @OA\Property(property="contact_no", type="integer"),
     *       @OA\Property(property="address", type="string")
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
     * create a new student
     */

    public function create(Request $request){

        $valid = Validator::make($request->all(),[
            'name' => 'required',
            'contact_no' => 'required|max:10|min:10',
            'address' => 'required'

        ]);

        if ($valid->fails()) {

            return response()->json([
                "status_code" => 401,
                "message" => "Error Occurred",
                "error" =>$valid->errors()
            ], 401);
        }

        try{
            $name = $request->name;
            $contact_no = $request->contact_no;
            $address = $request->address;

            DB::transaction(function () use ( $name, $contact_no, $address)
            {
                $student = Student::create([
                    "name" => $name,
                    "contact_no" => $contact_no,
                    "address" => $address
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

    /**
     * @OA\Put(
     *      path="/api/v1/document/student/update/4",
     *      tags={"Student"},
     *      summary="Update Student information",
     *      description="Returns Student data",
     *
     *     @OA\RequestBody(
     *    required=true,
     *    description="name,contact_no and address",
     *    @OA\JsonContent(
     *       required={"name","contact_no","address"},
     *       @OA\Property(property="name", type="string"),
     *       @OA\Property(property="contact_no", type="integer"),
     *       @OA\Property(property="address", type="string")
     *    ),
     * ),
     *
     *     @OA\Response(response="200", description="Successfully."),
     *     @OA\Response(response="404", description="Record Not found."),
     *     @OA\Response(response="400", description="Bad Request."),
     *     @OA\Response(response="500", description="Server Error.")
     * )
     */


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * update student by id
     */

    public function update(Request $request, $id) {

        $valid = Validator::make($request->all(),[
            'name' => 'required',
            'contact_no' => 'required|max:10|min:10',
            'address' => 'required'

        ]);

        if ($valid->fails()) {

            return response()->json([
                "status_code" => 401,
                "message" => "Error Occured",
                "error" =>$valid->errors()
            ], 401);
        }

        if (Student::where('id', $id)->exists()) {
            $student = Student::find($id);

            $student->name = is_null($request->name) ? $student->name : $request->name;
            $student->contact_no = is_null($request->contact_no) ? $student->contact_no : $request->contact_no;
            $student->address = is_null($request->address) ? $student->address : $request->address;
            $student->save();

            return response()->json([
                "status_code" => 200,
                "message" => "Record updated successfully",
                "data" => "null"
            ], 200);
        } else {
            return response()->json([
                "status_code" => 404,
                "message" => "Record not found",
                "data" => "null"
            ], 404);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/document/student/delete/5",
     *     tags={"Student"},
     *      summary="Delete Student information",
     *      description="Returns Student data",
     *
     *     @OA\Response(response="200", description="Successfully."),
     *     @OA\Response(response="404", description="Record Not found."),
     *     @OA\Response(response="400", description="Bad Request."),
     *     @OA\Response(response="500", description="Server Error.")
     * )
     */


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * delete student by id
     */

    public function delete($id) {
        if(Student::where('id', $id)->exists()) {
            $branch = Student::find($id);
            $branch->delete();

            return response()->json([
                "status_code" => 202,
                "message" => "Record deleted"
            ], 202);
        } else {
            return response()->json([
                "status_code" => 404,
                "message" => "Record not found"
            ], 404);
        }
    }

}
