<?php
/** English labels for IONOS Cloud inventory. */

Dict::Add('EN US', 'English', 'English', [
    'Cloud:IONOS' => 'IONOS',
    'Class:Cloud/Attribute:ionos_sec_auth_protection' => 'Additional access protection',
    'Class:Cloud/Attribute:ionos_sec_auth_protection+' => 'Whether IONOS requires additional authentication for this virtual datacenter.',
    'Class:Cloud/Attribute:ionos_sec_auth_protection/Value:unknown' => 'Unknown',
    'Class:Cloud/Attribute:ionos_sec_auth_protection/Value:yes' => 'Yes',
    'Class:Cloud/Attribute:ionos_sec_auth_protection/Value:no' => 'No',

    'Class:VirtualMachine/Attribute:ionos_server_id' => 'IONOS server ID',
    'Class:VirtualMachine/Attribute:ionos_vdc_id' => 'IONOS virtual datacenter ID',
    'Class:VirtualMachine/Attribute:ionos_source_key' => 'IONOS import source',
    'Class:VirtualMachine/Attribute:ionos_server_type' => 'IONOS server type',

    'Class:CloudNatGateway/Attribute:ionos_source_key' => 'IONOS import source',
    'Class:CloudNatGatewayLan/Attribute:ionos_source_key' => 'IONOS import source',
    'Class:CloudNatGatewayLanAddress/Attribute:ionos_source_key' => 'IONOS import source',
    'Class:CloudNatGatewayLanAddress/Attribute:ionos_synced_ip_id' => 'Last IPAM reference set by the IONOS collector',
    'Class:CloudNatGatewayPublicAddress/Attribute:ionos_source_key' => 'IONOS import source',
    'Class:CloudNatGatewayPublicAddress/Attribute:ionos_synced_ip_id' => 'Last IPAM reference set by the IONOS collector',

    'Class:CloudNetworkLoadBalancer/Attribute:ionos_source_key' => 'IONOS import source',
    'Class:CloudNetworkLoadBalancerListenerAddress/Attribute:ionos_source_key' => 'IONOS import source',
    'Class:CloudNetworkLoadBalancerListenerAddress/Attribute:ionos_synced_ip_id' => 'Last IPAM reference set by the IONOS collector',
    'Class:CloudNetworkLoadBalancerBackendAddress/Attribute:ionos_source_key' => 'IONOS import source',
    'Class:CloudNetworkLoadBalancerBackendAddress/Attribute:ionos_synced_ip_id' => 'Last IPAM reference set by the IONOS collector',
    'Class:CloudVpnGateway/Attribute:ionos_source_key' => 'IONOS import source',
    'Class:CloudVpnGateway/Attribute:ionos_region' => 'IONOS region',
    'Class:CloudVpnGateway/Attribute:ionos_tier' => 'IONOS tier',
    'Class:CloudVpnGateway/Attribute:ionos_status' => 'IONOS provisioning status',
    'Class:CloudVpnGateway/Attribute:ionos_synced_ip_id' => 'Last public endpoint set by the IONOS collector',
    'Class:CloudVpnGatewayLan/Attribute:ionos_source_key' => 'IONOS import source',
    'Class:CloudVpnGatewayLanAddress/Attribute:ionos_source_key' => 'IONOS import source',
    'Class:CloudVpnGatewayLanAddress/Attribute:ionos_synced_ip_id' => 'Last IPAM reference set by the IONOS collector',
    'Class:CloudVpnGatewayInterfaceAddress/Attribute:ionos_source_key' => 'IONOS import source',
    'Class:CloudVpnGatewayInterfaceAddress/Attribute:ionos_synced_ip_id' => 'Last IPAM reference set by the IONOS collector',
]);
