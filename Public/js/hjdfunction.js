// JavaScript Document


		function findSize(field_id)
		{	
				if($("#"+field_id)[0].files[0].name=="")//如果未上传则返回
				{
					return ;
				}else
				{
						//此处是循环文件对象 100足够
						for(i=0;i<=100;i++)
						{
							
							if($("#"+field_id)[0].files[i].name=="")
							{
								break;	
							}
							 var fileInput = $("#"+field_id)[0];
		       				 var byteSize  = fileInput.files[i].size;
		       				 var size= Math.ceil( byteSize / 1024);
							
							 if(size>2048)
							 {
								
								return false;	 
							 }
						}
				
				}
			   
			  
		       // Size returned in KB.
		}