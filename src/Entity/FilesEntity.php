<?php
namespace Worksection\SDK\Entity;

use Worksection\SDK\Entity;
use Worksection\SDK\Exception\SdkException;

class FilesEntity extends Entity
{
	/**
	 * @link https://worksection.com/en/faq/api-files.html
	 * To add files, use POST requests with each file transferred as a separate parameter named attach (n), where n is any seed value (see code example below).
	 *
	 * Files can be added in the following methods:
	 * post_project            - in ProjectsEntity, creating a project
	 * post_task, post_subtask - in TasksEntity, creating a task/subtask
	 * post_comment            - in CommentsEntity, posting a comment
	 *
	 * Sample of adding files in post_task request using PHP (curl) as an example below:
	 */
	  /*
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL,'https://your—domain.com/api/admin/v2/?action=post_task&page=/project/PROJECT_ID/&email_user_from=USER_EMAIL&email_user_to=USER_EMAIL&hidden=USER_EMAIL,USER_EMAIL&title=TASK_NAME&text=TASK_TEXT&datestart=DD.MM.YYYY&dateend=DD.MM.YYYY&tags=Tag1,Tag2&hash=HASH');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, [
			'attach[0]' = new cURLFile('path_to_file/local_file1.pdf','application/pdf','nice_name1.pdf'),
			'attach[1]' = new cURLFile('path_to_file/local_file2.pdf',
			'application/pdf','nice_name2.pdf'),
		]);
		$response = json_decode(curl_exec($curl), true);
		curl_close($curl);
	  */



	/**
	 * Allows downloading files attached to a task/subtask description or in comments
	 * Method does not apply to files attached to project descriptions or uploaded directly into the Files section
	 *
	 * @param int $fileId    Required. File ID can be obtained through get_task, get_tasks, get_all_tasks, get_comments requests using the extra=files parameter
	 * @return array
	 * @throws SdkException
	 * @link https://worksection.com/en/faq/api-files.html
	 */
	public function download(int $fileId): array
	{
		$params = [
			'action' => __FUNCTION__,
			'id_file' => $fileId
		];

		return $this->request($params);
	}



	/**
	 * Allows getting list of files in (project description, task/subtask description and comments)
	 *
	 * @param int $projectId    Required. Project ID
	 * @param int $taskId      Optional. Task ID
	 * @return array
	 * @throws SdkException
	 * @link https://worksection.com/en/faq/api-files.html
	 */
	public function get_files(int $projectId, int $taskId = 0): array
	{
		$params = [
			'action' => __FUNCTION__,
			'id_project' => $projectId
		];
		if ($taskId) $params['id_task'] = $taskId;

		return $this->request($params);
	}
}