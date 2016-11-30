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
							//判断对象长度 如果没有了则返回
							if($("#"+field_id)[0].files[i].name=="")
							{
								break;	
							}
							 var fileInput = $("#"+field_id)[0];//文件选择器
		       				 var byteSize  = fileInput.files[i].size;//单一文件大小
		       				 var size= Math.ceil( byteSize / 1024); 
							
							 if(size>2048)
							 {
								
								return false;	 
							 }
						}
				
				}
			   
			  
		       // Size returned in KB.
		}