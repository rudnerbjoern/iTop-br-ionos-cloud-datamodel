<?php
/** German labels for IONOS Cloud inventory. */

Dict::Add('DE DE', 'German', 'Deutsch', [
    'Cloud:IONOS' => 'IONOS',
    'Class:VirtualMachine/Attribute:ionos_server_id' => 'IONOS-Server-ID',
    'Class:VirtualMachine/Attribute:ionos_vdc_id' => 'IONOS-Rechenzentrums-ID',
    'Class:VirtualMachine/Attribute:ionos_source_key' => 'IONOS-Importquelle',
    'Class:VirtualMachine/Attribute:ionos_server_type' => 'IONOS-Servertyp',
    'Class:Cloud/Attribute:ionos_sec_auth_protection' => 'Zusätzlicher Zugriffsschutz',
    'Class:Cloud/Attribute:ionos_sec_auth_protection+' => 'Gibt an, ob IONOS für dieses virtuelle Datacenter eine zusätzliche Authentifizierung verlangt.',
    'Class:Cloud/Attribute:ionos_sec_auth_protection/Value:unknown' => 'Unbekannt',
    'Class:Cloud/Attribute:ionos_sec_auth_protection/Value:yes' => 'Ja',
    'Class:Cloud/Attribute:ionos_sec_auth_protection/Value:no' => 'Nein',

    'Class:CloudNatGateway/Attribute:ionos_source_key' => 'IONOS-Importquelle',
    'Class:CloudNatGatewayLan/Attribute:ionos_source_key' => 'IONOS-Importquelle',
    'Class:CloudNatGatewayLanAddress/Attribute:ionos_source_key' => 'IONOS-Importquelle',
    'Class:CloudNatGatewayLanAddress/Attribute:ionos_synced_ip_id' => 'Letzter vom IONOS-Collector gesetzter IPAM-Verweis',
    'Class:CloudNatGatewayPublicAddress/Attribute:ionos_source_key' => 'IONOS-Importquelle',
    'Class:CloudNatGatewayPublicAddress/Attribute:ionos_synced_ip_id' => 'Letzter vom IONOS-Collector gesetzter IPAM-Verweis',

    'Class:CloudNetworkLoadBalancer/Attribute:ionos_source_key' => 'IONOS-Importquelle',
    'Class:CloudNetworkLoadBalancerListenerAddress/Attribute:ionos_source_key' => 'IONOS-Importquelle',
    'Class:CloudNetworkLoadBalancerListenerAddress/Attribute:ionos_synced_ip_id' => 'Letzte vom IONOS-Collector gesetzte IPAM-Referenz',
    'Class:CloudNetworkLoadBalancerBackendAddress/Attribute:ionos_source_key' => 'IONOS-Importquelle',
    'Class:CloudNetworkLoadBalancerBackendAddress/Attribute:ionos_synced_ip_id' => 'Letzte vom IONOS-Collector gesetzte IPAM-Referenz',
    'Class:CloudVpnGateway/Attribute:ionos_source_key' => 'IONOS-Importquelle',
    'Class:CloudVpnGateway/Attribute:ionos_region' => 'IONOS-Region',
    'Class:CloudVpnGateway/Attribute:ionos_tier' => 'IONOS-Tarifstufe',
    'Class:CloudVpnGateway/Attribute:ionos_status' => 'IONOS-Bereitstellungsstatus',
    'Class:CloudVpnGateway/Attribute:ionos_synced_ip_id' => 'Letzter vom IONOS-Collector gesetzter öffentlicher Endpunkt',
    'Class:CloudVpnGatewayLan/Attribute:ionos_source_key' => 'IONOS-Importquelle',
    'Class:CloudVpnGatewayLanAddress/Attribute:ionos_source_key' => 'IONOS-Importquelle',
    'Class:CloudVpnGatewayLanAddress/Attribute:ionos_synced_ip_id' => 'Letzter vom IONOS-Collector gesetzter IPAM-Verweis',
    'Class:CloudVpnGatewayInterfaceAddress/Attribute:ionos_source_key' => 'IONOS-Importquelle',
    'Class:CloudVpnGatewayInterfaceAddress/Attribute:ionos_synced_ip_id' => 'Letzter vom IONOS-Collector gesetzter IPAM-Verweis',
]);
