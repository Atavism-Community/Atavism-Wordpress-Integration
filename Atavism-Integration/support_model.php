<?php


class support_model  {
    public function getTimestamp()
    {
        $date = new DateTime();
        return $date->getTimestamp();
    }
	public function getOpenSupportTickets()
	{
        global $wpdb;
        $db = $wpdb::query;
		$qq = $db->select('*')
				->where('close !=', '1')
                ->order_by('id', 'ASC')
                ->get('fx_support');
		if ($qq->num_rows())
			return $qq->num_rows();
        else
            return 0;
	}
	public function getSupportTickets()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . "support";
        $output = '';
		$author = get_current_user_id();
		if (current_user_can('administrator')) {
            $rows = $wpdb->get_results("select * FROM $table_name WHERE close != 1 ORDER BY id ASC;", OBJECT );
		}else{
            $rows = $wpdb->get_results("select * FROM $table_name WHERE close != 1 AND WHERE 'author' = $author ORDER BY id ASC;", OBJECT );
		}
        foreach ($rows as $row)
        {
            $output .= '
                <tr>
                    <td>
                        <a href="./incident.php?ticket='.$row->id.'">
                            <span class="uk-dark">'.$row->id.'</span>
                        </a>
                    </td>
                    <td class="uk-text-center">
                        <a href="./incident.php?ticket='.$row->id.'">
                            <span class="uk-dark">'.$row->title.'</span>
                        </a>
                    </td>
                    <td class="uk-text-center">
                        <a href="./incident.php?ticket='.$row->id.'">
                            <span class="uk-dark">'.gmdate("F j Y @ g:i a", $row->date).'</span>
                        </a>
                    </td>
                    <td class="uk-text-center">
                        <a href="./incident.php?ticket='.$row->id.'">
						
                            <span class="uk-dark">'.support_model::getCategory($row->category).'</span>
                        </a>
                    </td>
                    <td class="uk-text-center">
                        <a href="./incident.php?ticket='.$row->id.'">
                            <span class="uk-dark">'.support_model::getStatus($row->status).'</span>
                        </a>
                    </td>
                <tr>
            ';
        }

        $output .= '</tbody> </table>';
        return $output;
    }
	public function insertIssue($title, $category, $desc)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . "support";
        $date = support_model::getTimestamp();
        $author = get_current_user_id();
        $data = array(
            'title' => $title,
            'description' => $desc,
            'category' => $category,
            'date' => $date,
            'close' => '0',
            'author' => $author,
			'status' => '1',
			'priority' => '2'
            );
        $wpdb->insert($table_name,$data);
        $redirect= './tickets.php';
        wp_redirect( $redirect );
    }
	public function getTitleIssue($id)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . "support";
        $row = $wpdb->get_var("select title FROM $table_name WHERE id = $id;");
        return $row;
    }
	public function getDescIssue($id)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . "support";
        $row = $wpdb->get_var("select description FROM $table_name WHERE id = $id;");
        return $row;
    }
	public function getStatusID($id)
    {
        global $wpdb,$table_prefix;
        $table_name = $wpdb->prefix . "support";
        $row = $wpdb->get_var("select status FROM $table_name WHERE id = $id;");
        return $row;
    }
	public function getStatus($id)
    {
        global $wpdb,$table_prefix;
        $table_name = $wpdb->prefix . "support_status";
        $row = $wpdb->get_var("select title FROM $table_name WHERE id = $id;");
        return $row;
    }
	public function getStatusGeneral()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . "support_status";
        $row = $wpdb->get_results("select * FROM $table_name;");
        return $row;
    }
	public function getCategory($id)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . "support_category";
        $row = $wpdb->get_var("select title FROM $table_name WHERE id = $id;");
        return $row;
    }
	public function getCategoryID($id)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . "support";
        $row = $wpdb->get_var("select category FROM $table_name WHERE id = $id;");
        return $row;
    }
	public function getCategoryGeneral()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . "support_category";
        $row = $wpdb->get_results("select * FROM $table_name;");
        return $row;
    }
	public function getPriority($id)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . "support_priority";
        $row = $wpdb->get_var("select title FROM $table_name WHERE id = $id;");
        return $row;
    }
	public function getPriorityID($id)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . "support";
        $row = $wpdb->get_var("select priority FROM $table_name WHERE id = $id;");
        return $row;
    }
	public function getPriorityGeneral()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . "support_priority";
        $row = $wpdb->get_results("select * FROM $table_name;");
        return $row;
    }
	public function getDate($id)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . "support";
        $row = $wpdb->get_var("select date FROM $table_name WHERE id = $id;");
        return $row;
    }
	public function closeStatus($id)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . "support";
        $row = $wpdb->get_var("select close FROM $table_name WHERE id = $id;");
        return $row;
    }

    public function getAuthor($id)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . "support";
        $row = $wpdb->get_var("select author FROM $table_name WHERE id = $id;");
        return $row;
    }
	
	public function changePriority($id, $priority)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . "support";
        $wpdb->query($wpdb->prepare("UPDATE $table_name SET priority=$priority WHERE id = $id",''));
        $redirect= './incident.php?';
        wp_redirect( add_query_arg( 'ticket', $id, $redirect ) );
    }

    public function closeIssue($id)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . "support";
        $wpdb->query($wpdb->prepare("UPDATE $table_name SET close='1' WHERE id = $id",''));
        $redirect= './tickets.php';
        wp_redirect( $redirect );
    }

    public function changeCategory($id, $type)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . "support";
        $wpdb->query($wpdb->prepare("UPDATE $table_name SET category=$type WHERE id = $id",''));
        $redirect= './incident.php?';
        wp_redirect( add_query_arg( 'ticket', $id, $redirect ) );
    }

    public function changeStatus($id, $status)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . "support";
        $wpdb->query($wpdb->prepare("UPDATE $table_name SET status=$status WHERE id = $id",''));
        $redirect= './incident.php?';
        wp_redirect( add_query_arg( 'ticket', $id, $redirect ) );
    }
}
