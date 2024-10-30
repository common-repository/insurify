<?php 

defined( 'ABSPATH' ) || exit;

?>
<div class="min-h-screen bg-gray-100">
	<?php require ECOMSURANCE__PLUGIN_DIR.'views/includes/navigation.php'; ?>
	<header class="bg-white shadow">
		<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
			<h2 class="font-semibold text-xl text-gray-800 leading-tight"> Email Templates </h2>
		</div>
	</header>
	<div class="py-12">
		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
			<div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
				<table class="min-w-max w-full table-auto">
					<thead>
						<tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
							<th class="py-3 text-left px-3">Email Template name</th>
							<th class="py-3 text-left px-3">Notification type</th> 
							<th class="py-3 text-left px-3">Email Status</th>  
							<th class="py-3 text-left px-3">Action</th> 
						</tr>
					</thead>
					<tbody class="text-gray-600 text-sm font-light">
						<tr class="border-b border-gray-200 hover:bg-gray-100">
							<td class="py-3 px-6 text-left whitespace-nowrap">Claim Requested Email For Admin</td>
							<td class="py-3 px-6 text-left whitespace-nowrap">Filed For Admin</td>
							<td class="py-3 px-6 text-left whitespace-nowrap">Active</td> 
							<td class="py-3 px-6 text-center">
								<div class="flex item-center justify-center">
									<a class="w-4 mr-2 transform hover:text-purple-500 hover:scale-110" href="?page=ecomsurance-email-templates-edit&id=1">
										<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
									</a>
								</div> 
							</td>
						</tr>
						<tr class="border-b border-gray-200 hover:bg-gray-100">
							<td class="py-3 px-6 text-left whitespace-nowrap">Claim Requested Email For Customer</td>
							<td class="py-3 px-6 text-left whitespace-nowrap">Filed</td>
							<td class="py-3 px-6 text-left whitespace-nowrap">Active</td> 
							<td class="py-3 px-6 text-center">
								<div class="flex item-center justify-center">
									<a class="w-4 mr-2 transform hover:text-purple-500 hover:scale-110" href="?page=ecomsurance-email-templates-edit&id=2">
										<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
									</a>
								</div> 
							</td>
						</tr>
						<tr class="border-b border-gray-200 hover:bg-gray-100">
							<td class="py-3 px-6 text-left whitespace-nowrap">Claim In Progress Email For Customer</td>
							<td class="py-3 px-6 text-left whitespace-nowrap">In Progress</td>
							<td class="py-3 px-6 text-left whitespace-nowrap">Active</td> 
							<td class="py-3 px-6 text-center">
								<div class="flex item-center justify-center">
									<a class="w-4 mr-2 transform hover:text-purple-500 hover:scale-110" href="?page=ecomsurance-email-templates-edit&id=3">
										<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
									</a>
								</div> 
							</td>
						</tr>
						<tr class="border-b border-gray-200 hover:bg-gray-100">
							<td class="py-3 px-6 text-left whitespace-nowrap">Claim Reorder Email For Customer</td>
							<td class="py-3 px-6 text-left whitespace-nowrap">Reorder</td>
							<td class="py-3 px-6 text-left whitespace-nowrap">Active</td> 
							<td class="py-3 px-6 text-center">
								<div class="flex item-center justify-center">
									<a class="w-4 mr-2 transform hover:text-purple-500 hover:scale-110" href="?page=ecomsurance-email-templates-edit&id=4">
										<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
									</a>
								</div> 
							</td>
						</tr>
						<tr class="border-b border-gray-200 hover:bg-gray-100">
							<td class="py-3 px-6 text-left whitespace-nowrap">Claim Refund Email For Customer</td>
							<td class="py-3 px-6 text-left whitespace-nowrap">Refund</td>
							<td class="py-3 px-6 text-left whitespace-nowrap">Active</td> 
							<td class="py-3 px-6 text-center">
								<div class="flex item-center justify-center">
									<a class="w-4 mr-2 transform hover:text-purple-500 hover:scale-110" href="?page=ecomsurance-email-templates-edit&id=5">
										<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
									</a>
								</div> 
							</td>
						</tr>
						<tr class="border-b border-gray-200 hover:bg-gray-100">
							<td class="py-3 px-6 text-left whitespace-nowrap">Claim Other Email For Customer</td>
							<td class="py-3 px-6 text-left whitespace-nowrap">Other</td>
							<td class="py-3 px-6 text-left whitespace-nowrap">Active</td> 
							<td class="py-3 px-6 text-center">
								<div class="flex item-center justify-center">
									<a class="w-4 mr-2 transform hover:text-purple-500 hover:scale-110" href="?page=ecomsurance-email-templates-edit&id=6">
										<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
									</a>
								</div> 
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>